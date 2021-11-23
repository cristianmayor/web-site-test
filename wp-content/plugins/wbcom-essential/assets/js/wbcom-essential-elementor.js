(function ($) {

	'use strict';

	var WBComEssentialSectionsData = window.WBComEssentialSectionsData || {},
		WBComEssentialSectionsEditor,
		WBComEssentialSectionsEditorViews;

	WBComEssentialSectionsEditorViews = {

		ModalLayoutView: null,
		ModalHeaderView: null,
		ModalLoadingView: null,
		ModalBodyView: null,
		ModalErrorView: null,
		LibraryCollection: null,
		ModalCollectionView: null,
		ModalTabsCollection: null,
		ModalTabsCollectionView: null,
		FiltersCollectionView: null,
		FiltersItemView: null,
		ModalTabsItemView: null,
		ModalTemplateItemView: null,
		ModalInsertTemplateBehavior: null,
		ModalTemplateModel: null,
		CategoriesCollection: null,
		ModalHeaderLogo: null,
		TabModel: null,
		CategoryModel: null,
		TemplatesEmptyView: null,
		TemplateSearchCollectionView: null,

		init: function () {
			var self = this;

			self.ModalTemplateModel = Backbone.Model.extend({
				defaults: {
					template_id: 0,
					title: '',
					thumbnail: '',
					source: '',
					categories: []
				}
			});

			self.ModalHeaderView = Marionette.LayoutView.extend({

				id: 'wbcom-essential-template-modal-header',
				template: '#tmpl-wbcom-essential-template-modal-header',

				ui: {
					closeModal: '#wbcom-essential-template-modal-header-close-modal'
				},

				events: {
					'click @ui.closeModal': 'onCloseModalClick'
				},

				regions: {
					headerLogo: '#wbcom-essential-template-modal-header-logo-area',
					headerTabs: '#wbcom-essential-template-modal-header-tabs',
					headerActions: '#wbcom-essential-template-modal-header-actions'
				},

				onCloseModalClick: function () {
					WBComEssentialSectionsEditor.closeModal();
				}

			});

			self.TabModel = Backbone.Model.extend({
				defaults: {
					slug: '',
					title: ''
				}
			});

			self.LibraryCollection = Backbone.Collection.extend({
				model: self.ModalTemplateModel
			});

			self.ModalTabsCollection = Backbone.Collection.extend({
				model: self.TabModel
			});

			self.CategoryModel = Backbone.Model.extend({
				defaults: {
					slug: '',
					title: ''
				}
			});

			self.CategoriesCollection = Backbone.Collection.extend({
				model: self.CategoryModel
			});

			self.ModalHeaderLogo = Marionette.ItemView.extend({

				template: '#tmpl-wbcom-essential-template-modal-header-logo',

				id: 'wbcom-essential-template-modal-header-logo'

			});

			self.ModalBodyView = Marionette.LayoutView.extend({

				id: 'wbcom-essential-template-library-content',

				className: function () {
					return 'library-tab-' + WBComEssentialSectionsEditor.getTab();
				},

				template: '#tmpl-wbcom-essential-template-modal-content',

				regions: {
					contentTemplates: '.wbcom-essential-templates-list',
					contentFilters: '.wbcom-essential-filters-list',
					contentSearch: '#elementor-template-library-filter-text-wrapper',
				}

			});

			self.TemplatesEmptyView = Marionette.LayoutView.extend({

				id: 'wbcom-essential-template-modal-empty',

				template: '#tmpl-wbcom-essential-template-modal-empty',

				ui: {
					title: '.elementor-template-library-blank-title',
				},

				regions: {
					contentTemplates: '.wbcom-essential-templates-list',
					contentFilters: '.wbcom-essential-filters-list',
					contentSearch: '#elementor-template-library-filter-text-wrapper',
				}

			});

			self.ModalInsertTemplateBehavior = Marionette.Behavior.extend({
				ui: {
					insertButton: '.wbcom-essential-template-insert'
				},

				events: {
					'click @ui.insertButton': 'onInsertButtonClick'
				},

				onInsertButtonClick: function () {

					var templateModel = this.view.model,
						options = {};

					WBComEssentialSectionsEditor.layout.showLoadingView();
					$.ajax({
						url: ajaxurl,
						type: 'post',
						dataType: 'json',
						data: {
							action: 'bb_elementor_sections_inner_template',
							template: templateModel.attributes,
							tab: WBComEssentialSectionsEditor.getTab()
						}
					});

					elementor.templates.requestTemplateContent(
						templateModel.get('source'),
						templateModel.get('template_id'),
						{
							data: {
								tab: WBComEssentialSectionsEditor.getTab(),
								page_settings: false
							},
							success: function (data) {

								console.log("%c Template Inserted Successfully!!", "color: #7a7a7a; background-color: #eee;");

								WBComEssentialSectionsEditor.closeModal();

								elementor.channels.data.trigger('template:before:insert', templateModel);

								if (null !== WBComEssentialSectionsEditor.atIndex) {
									options.at = WBComEssentialSectionsEditor.atIndex;
								}

								elementor.previewView.addChildModel(data.content, options);

								elementor.channels.data.trigger('template:after:insert', templateModel);

								WBComEssentialSectionsEditor.atIndex = null;
								jQuery('.elementor-button-success').removeClass('elementor-disabled');
							},
							error: function (err) {
								WBComEssentialSectionsEditor.closeModal();
							}
						}
					);
				}
			});

			self.FiltersItemView = Marionette.ItemView.extend({

				template: '#tmpl-wbcom-essential-template-modal-filters-item',

				className: function () {
					return 'wbcom-essential-template-filter-item';
				},

				ui: function () {
					return {
						filterLabels: '.wbcom-essential-template-filter-label'
					};
				},

				events: function () {
					return {
						'click @ui.filterLabels': 'onFilterClick'
					};
				},

				onFilterClick: function (event) {

					var $clickedInput = jQuery(event.target);
					WBComEssentialSectionsEditor.setFilter('category', $clickedInput.val());
				}

			});

			self.TemplateSearchCollectionView = Marionette.CompositeView.extend({

				template: '#tmpl-wbcom-essential-template-modal-search-item',
				id: 'wbcom-essential-template-modal-search-item',

				ui: function () {
					return {
						textFilter: '#elementor-template-library-filter-text',
					};
				},

				events: function () {
					return {
						'input @ui.textFilter': 'onTextFilterInput',
					};
				},

				onTextFilterInput: function onTextFilterInput( childModel ) {

					var searchText = this.ui.textFilter.val();

					WBComEssentialSectionsEditor.setFilter('text', searchText);
				},

			});

			self.ModalTabsItemView = Marionette.ItemView.extend({

				template: '#tmpl-wbcom-essential-template-modal-tabs-item',

				className: function () {
					return 'elementor-template-library-menu-item';
				},

				ui: function () {
					return {
						tabsLabels: 'label',
						tabsInput: 'input'
					};
				},

				events: function () {
					return {
						'click @ui.tabsLabels': 'onTabClick'
					};
				},

				onRender: function () {
					if (this.model.get('slug') === WBComEssentialSectionsEditor.getTab()) {
						this.ui.tabsInput.attr('checked', 'checked');
					}
				},

				onTabClick: function (event) {

					var $clickedInput = jQuery(event.target);
					WBComEssentialSectionsEditor.setTab($clickedInput.val());
				}

			});

			self.FiltersCollectionView = Marionette.CompositeView.extend({

				id: 'wbcom-essential-template-library-filters',

				template: '#tmpl-wbcom-essential-template-modal-filters',

				childViewContainer: '#wbcom-essential-modal-filters-container',

				getChildView: function (childModel) {
					return self.FiltersItemView;
				}

			});

			self.ModalTabsCollectionView = Marionette.CompositeView.extend({

				template: '#tmpl-wbcom-essential-template-modal-tabs',

				childViewContainer: '#wbcom-essential-modal-tabs-items',

				initialize: function () {
					this.listenTo(WBComEssentialSectionsEditor.channels.layout, 'tamplate:cloned', this._renderChildren);
				},

				getChildView: function (childModel) {
					return self.ModalTabsItemView;
				}

			});

			self.ModalTemplateItemView = Marionette.ItemView.extend({

				template: '#tmpl-wbcom-essential-template-modal-item',

				className: function () {

					var urlClass = ' wbcom-essential-template-has-url',
						sourceClass = ' elementor-template-library-template-';

					sourceClass += 'remote';

					return 'elementor-template-library-template' + sourceClass + urlClass;
				},

				ui: function () {
					return {
						previewButton: '.elementor-template-library-template-preview',
					};
				},

				behaviors: {
					insertTemplate: {
						behaviorClass: self.ModalInsertTemplateBehavior
					}
				}
			});

			self.ModalCollectionView = Marionette.CompositeView.extend({

				template: '#tmpl-wbcom-essential-template-modal-templates',

				id: 'wbcom-essential-template-library-templates',

				childViewContainer: '#wbcom-essential-modal-templates-container',

				emptyView: function emptyView() {

					return new self.TemplatesEmptyView();
				},

				initialize: function () {

					this.listenTo(WBComEssentialSectionsEditor.channels.templates, 'filter:change', this._renderChildren);
				},

				filter: function (childModel) {

					var filter = WBComEssentialSectionsEditor.getFilter('category');
					var searchText = WBComEssentialSectionsEditor.getFilter('text');

					if (!filter && !searchText) {
						return true;
					}

					if (filter && !searchText) {
						return _.contains(childModel.get('categories'), filter);
					}

					if (searchText && !filter) {
						if (childModel.get('title').toLowerCase().indexOf(searchText) >= 0) {
							return true;
						}
					}

					if (searchText && filter) {
						return _.contains(childModel.get('categories'), filter) && childModel.get('title').toLowerCase().indexOf(searchText) >= 0;
					}

				},

				getChildView: function (childModel) {
					return self.ModalTemplateItemView;
				},

				onRenderCollection: function () {

					var container = this.$childViewContainer,
						items = this.$childViewContainer.children(),
						tab = WBComEssentialSectionsEditor.getTab();

					if ('bb_elementor_sections_page' === tab || 'local' === tab) {
						return;
					}

					// Wait for thumbnails to be loaded.
					container.imagesLoaded(function () { }).done(function () {
						self.masonry.init({
							container: container,
							items: items
						});
					});
				}

			});

			self.ModalLayoutView = Marionette.LayoutView.extend({

				el: '#wbcom-essential-template-modal',

				regions: WBComEssentialSectionsData.modalRegions,

				initialize: function () {

					this.getRegion('modalHeader').show(new self.ModalHeaderView());
					this.listenTo(WBComEssentialSectionsEditor.channels.tabs, 'filter:change', this.switchTabs);

				},

				switchTabs: function () {
					this.showLoadingView();
					WBComEssentialSectionsEditor.requestTemplates(WBComEssentialSectionsEditor.getTab());
				},

				getHeaderView: function () {
					return this.getRegion('modalHeader').currentView;
				},

				getContentView: function () {
					return this.getRegion('modalContent').currentView;
				},

				showLoadingView: function () {
					this.modalContent.show(new self.ModalLoadingView());
				},

				showError: function () {
					this.modalContent.show(new self.ModalErrorView());
				},

				showTemplatesView: function (templatesCollection, categoriesCollection ) {

					if( 0 !== templatesCollection.length ) {
						this.getRegion('modalContent').show(new self.ModalBodyView());
						var contentView = this.getContentView(),
							header = this.getHeaderView();

						WBComEssentialSectionsEditor.collections.tabs = new self.ModalTabsCollection(WBComEssentialSectionsEditor.getTabs());

						header.headerTabs.show(new self.ModalTabsCollectionView({
							collection: WBComEssentialSectionsEditor.collections.tabs
						}));

						contentView.contentTemplates.show(new self.ModalCollectionView({
							collection: templatesCollection
						}));

						contentView.contentFilters.show(new self.FiltersCollectionView({
							collection: categoriesCollection
						}));

						contentView.contentSearch.show(new self.TemplateSearchCollectionView());

					} else {
						this.getRegion('modalContent').show(new self.TemplatesEmptyView());
					}

				}

			});

			self.ModalLoadingView = Marionette.ItemView.extend({
				id: 'wbcom-essential-template-modal-loading',
				template: '#tmpl-wbcom-essential-template-modal-loading'
			});

			self.ModalErrorView = Marionette.ItemView.extend({
				id: 'wbcom-essential-template-modal-error',
				template: '#tmpl-wbcom-essential-template-modal-error'
			});

		},

		masonry: {

			self: {},
			elements: {},

			init: function (settings) {

				var self = this;
				self.settings = $.extend(self.getDefaultSettings(), settings);
				self.elements = self.getDefaultElements();

				self.run();
			},

			getSettings: function (key) {
				if (key) {
					return this.settings[key];
				} else {
					return this.settings;
				}
			},

			getDefaultSettings: function () {
				return {
					container: null,
					items: null,
					columnsCount: 3,
					verticalSpaceBetween: 30
				};
			},

			getDefaultElements: function () {
				return {
					$container: jQuery(this.getSettings('container')),
					$items: jQuery(this.getSettings('items'))
				};
			},

			run: function () {
				var heights = [],
					distanceFromTop = this.elements.$container.position().top,
					settings = this.getSettings(),
					columnsCount = settings.columnsCount;

				distanceFromTop += parseInt(this.elements.$container.css('margin-top'), 10);

				this.elements.$container.height('');

				this.elements.$items.each(function (index) {
					var row = Math.floor(index / columnsCount),
						indexAtRow = index % columnsCount,
						$item = jQuery(this),
						itemPosition = $item.position(),
						itemHeight = $item[0].getBoundingClientRect().height + settings.verticalSpaceBetween;

					if (row) {
						var pullHeight = itemPosition.top - distanceFromTop - heights[indexAtRow];
						pullHeight -= parseInt($item.css('margin-top'), 10);
						pullHeight *= -1;
						$item.css('margin-top', pullHeight + 'px');
						heights[indexAtRow] += itemHeight;
					} else {
						heights.push(itemHeight);
					}
				});

				this.elements.$container.height(Math.max.apply(Math, heights));
			}
		}

	};

	WBComEssentialSectionsEditor = {
		modal: false,
		layout: false,
		collections: {},
		tabs: {},
		defaultTab: '',
		channels: {},
		atIndex: null,

		init: function () {

			window.elementor.on(
				'document:loaded',
				window._.bind(WBComEssentialSectionsEditor.onPreviewLoaded, WBComEssentialSectionsEditor)
			);

			WBComEssentialSectionsEditorViews.init();

		},

		onPreviewLoaded: function () {

			this.initWBComEssentialSectionsTempsButton();

			window.elementor.$previewContents.on(
				'click.addWBComEssentialSectionsTemplate',
				'.wbcom-essential-add-section-btn',
				_.bind(this.showTemplatesModal, this)
			);

			this.channels = {
				templates: Backbone.Radio.channel('BBELEMENTOR_EDITOR:templates'),
				tabs: Backbone.Radio.channel('BBELEMENTOR_EDITOR:tabs'),
				layout: Backbone.Radio.channel('BBELEMENTOR_EDITOR:layout'),
			};

			this.tabs = WBComEssentialSectionsData.tabs;
			this.defaultTab = WBComEssentialSectionsData.defaultTab;

		},

		initWBComEssentialSectionsTempsButton: function () {

			setTimeout(function () {
				var $addNewSection = window.elementor.$previewContents.find('.elementor-add-new-section'),
					addWBComEssentialSectionsTemplate = "<div class='elementor-add-section-area-button wbcom-essential-add-section-btn' title='Add Elementor Sections Template'><img src='"+WBComEssentialSectionsData.icon+"'></div>",
					$addWBComEssentialSectionsTemplate;

				if ($addNewSection.length) {
					$addWBComEssentialSectionsTemplate = $(addWBComEssentialSectionsTemplate).prependTo($addNewSection);
				}
			
        window.elementor.$previewContents.on(
            'click.addWBComEssentialSectionsTemplate',
            '.elementor-editor-section-settings .elementor-editor-element-add',
            function () {

                var $this = $(this),
                    $section = $this.closest('.elementor-top-section'),
                    modelID = $section.data('model-cid');



	            if (-1 !== WBComEssentialSectionsData.Elementor_Version.indexOf('3.0.')) {
		            if (window.elementor.previewView.collection.length) {
			            $.each(window.elementor.previewView.collection.models, function (index, model) {
				            if (modelID === model.cid) {
					            WBComEssentialSectionsEditor.atIndex = index;
				            }
			            });
		            }
	            } else {
		            if (window.elementor.sections.currentView.collection.length) {
			            $.each(window.elementor.sections.currentView.collection.models, function (index, model) {
				            if (modelID === model.cid) {
					            WBComEssentialSectionsEditor.atIndex = index;
				            }
			            });
		            }
	            }


								setTimeout(function () {
									var $addNew = $section.prev('.elementor-add-section').find('.elementor-add-new-section');
									$addNew.prepend(addWBComEssentialSectionsTemplate);
								}, 100);

            }
        );
            }, 100);
		},

		getFilter: function (name) {

			return this.channels.templates.request('filter:' + name);
		},

		setFilter: function (name, value) {
			this.channels.templates.reply('filter:' + name, value);
			this.channels.templates.trigger('filter:change');
		},

		getTab: function () {
			return this.channels.tabs.request('filter:tabs');
		},

		setTab: function (value, silent) {

			this.channels.tabs.reply('filter:tabs', value);

			if (!silent) {
				this.channels.tabs.trigger('filter:change');
			}

		},

		getTabs: function () {

			var tabs = [];

			_.each(this.tabs, function (item, slug) {
				tabs.push({
					slug: slug,
					title: item.title
				});
			});

			return tabs;
		},

		showTemplatesModal: function () {

			this.getModal().show();
			if (!this.layout) {
				this.layout = new WBComEssentialSectionsEditorViews.ModalLayoutView();
				this.layout.showLoadingView();
			}

			this.setTab(this.defaultTab, true);
			this.requestTemplates(this.defaultTab);
			

		},

		requestTemplates: function (tabName) {

			if( '' === tabName ) {
				return;
			}

			var self = this,
				tab = self.tabs[tabName];

			self.setFilter('category', false);

			if (tab.data.templates && tab.data.categories) {
				self.layout.showTemplatesView(tab.data.templates, tab.data.categories);
			} else {
				$.ajax({
					url: ajaxurl,
					type: 'get',
					dataType: 'json',
					data: {
						action: 'bb_elementor_sections_get_templates',
						tab: tabName
					},
					success: function (response) {
						console.log("%cTemplates Retrieved Successfully!!", "color: #7a7a7a; background-color: #eee;");

						var templates = new WBComEssentialSectionsEditorViews.LibraryCollection(response.data.templates),
							categories = new WBComEssentialSectionsEditorViews.CategoriesCollection(response.data.categories);

						self.tabs[tabName].data = {
							templates: templates,
							categories: categories,
						};

						self.layout.showTemplatesView(templates, categories );

					},
					error: function (err) {
						WBComEssentialSectionsEditor.closeModal();
					}
				});
			}

		},

		closeModal: function () {
			this.getModal().hide();
		},

		getModal: function () {

			if (!this.modal) {
				this.modal = elementor.dialogsManager.createWidget('lightbox', {
					id: 'wbcom-essential-template-modal',
					className: 'elementor-templates-modal',
					closeButton: false
				});
			}

			return this.modal;

		}

	};

	$(window).on('elementor:init', WBComEssentialSectionsEditor.init);

})(jQuery);