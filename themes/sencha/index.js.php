
Ext.ns('wipad', 'Ext.ux');

Ext.ux.UniversalUI = Ext.extend(Ext.Panel, {
    fullscreen: true,
    layout: 'card',
    cls: 'the_body',
    
    initComponent : function() {
        this.backButton = new Ext.Button({
            hidden: true,
            text: 'Back',
            ui: 'back',
            handler: this.onBackButtonTap,
            scope: this
        });

        this.navigationButton = new Ext.Button({
            hidden: Ext.platform.isPhone || Ext.orientation == 'landscape',
            text: 'Navigation',
            handler: this.onNavButtonTap,
            scope: this
        });
        
        this.navigationBar = new Ext.Toolbar({
            ui: 'dark',
            dock: 'top',
            title: this.title,
            items: [this.backButton, this.navigationButton].concat(this.buttons || [])
        });
        
        this.navigationPanel = new Ext.NestedList({
            items: this.navigationItems || [],
            dock: 'left',
            width: 250,
            height: 456,
            hidden: !Ext.platform.isPhone && Ext.orientation == 'portrait',
            toolbar: Ext.platform.isPhone ? this.navigationBar : null,
            listeners: {
                listchange: this.onListChange,
                scope: this
            }
        });
        
        this.dockedItems = this.dockedItems || [];
        this.dockedItems.unshift(this.navigationBar);
        
        if (!Ext.platform.isPhone && Ext.orientation == 'landscape') {
            this.dockedItems.unshift(this.navigationPanel);
        }
        else if (Ext.platform.isPhone) {
            this.items = this.items || [];
            this.items.unshift(this.navigationPanel);
        }
        
        this.addEvents('navigate');
        
        Ext.ux.UniversalUI.superclass.initComponent.call(this);
    },
    
    onListChange : function(list, item) {
        if (Ext.orientation == 'portrait' && !Ext.platform.isPhone && !item.items && !item.preventHide) {
            this.navigationPanel.hide();
        }
        
        if (item.card) {
            this.setCard(item.card, item.animation || 'slide');
            if(item.the_hash){
              window.location.hash = item.the_hash;
            }
            this.currentCard = item.card;
            if (item.text) {
                this.navigationBar.setTitle(item.text);
            }
            if (Ext.platform.isPhone) {
                this.backButton.show();
                this.navigationBar.doLayout();
            }
        }     
       
        this.fireEvent('navigate', this, item, list);
    },
    
    onNavButtonTap : function() {
        this.navigationPanel.showBy(this.navigationButton, 'fade');
    },
    
    onBackButtonTap : function() {
        this.setCard(this.navigationPanel, {type: 'slide', direction: 'right'});
        this.currentCard = this.navigationPanel;
        if (Ext.platform.isPhone) {
            this.backButton.hide();
            this.navigationBar.setTitle(this.title);
            this.navigationBar.doLayout();
        }
        this.fireEvent('navigate', this, this.navigationPanel.activeItem, this.navigationPanel);
    },
    
    onOrientationChange : function(orientation, w, h) {
        Ext.ux.UniversalUI.superclass.onOrientationChange.call(this, orientation, w, h);
        
        if (!Ext.platform.isPhone) {
            if (orientation == 'portrait') {
                this.removeDocked(this.navigationPanel, false);
                this.navigationPanel.hide();
                this.navigationPanel.setFloating(true);
                this.navigationButton.show();
            }
            else {                
                this.navigationPanel.setFloating(false);
                this.navigationPanel.show();
                this.navigationButton.hide();
                this.insertDocked(0, this.navigationPanel);                
            }

            this.doComponentLayout();
            this.navigationBar.doComponentLayout();
        }
    }
});

wipad.Main = {
    init : function(title, html) {

        var sourceConfig = {
            items: [],
            scroll: 'both'
        };

        if (!Ext.platform.isPhone) {
            Ext.apply(sourceConfig, {
                width: 500,
                height: 500,
                floating: true
            });
        }
                
        this.sourcePanel = new Ext.Panel(sourceConfig);
      
        this.ui = new Ext.ux.UniversalUI({
            title: (title == null) ? "<?php echo wp_title('&laquo;', true, 'right').stripslashes($str) ?>" : title,
            navigationItems: <?php include(compat_get_plugin_dir('wipad')."/themes/sencha/_post_list.js.php")?>,
            items: [{html: html}],
            listeners: {
                navigate : this.onNavigate,
                scope: this
            }
        });
    },
    
    onNavigate : function(ui, item) {
        if(item.load_source && !item.items) {
              Ext.Ajax.request({
                  url: item.load_source,
                  success: function(response) {
                     item.items = eval(response.responseText);
                     ui.navigationPanel.onItemTap(item);
                  },
                  scope: this
              });
        }else{
          this.sourceActive = false;
          ui.navigationBar.doComponentLayout();
        }

    },
};

Ext.setup({
    tabletStartupScreen: '<?php echo $resource_base_url  ?>/img/tablet_startup.png',
    phoneStartupScreen: '<?php echo $resource_base_url  ?>/img/phone_startup.png',
    icon: '<?php echo $resource_base_url  ?>/img/icon.png',
    glossOnIcon: false,
    
    onReady: function() {
      if (window.location.hash == ''){
        wipad.Main.init();
      }else{
        Ext.Ajax.request({
            url: "<?php echo get_bloginfo('wpurl')?>" + window.location.hash.substring(1),
            success: function(response) { 
              item_array = eval(response.responseText);
              wipad.Main.init((item_array[0]=="") ? null : item_array[0], item_array[1]);
            }
        });
      }
    }
});