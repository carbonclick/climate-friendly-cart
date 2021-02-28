define( 
   [ 
     'jquery', 
     'Magento_Ui/js/modal/modal' 
   ], 
   function($) { 
      "use strict"; 
      //creating jquery widget 
      $.widget('Carbonclick.Popup', { 
         options: { 
            modalForm: '#redeem_popup', 
            modalButton: '.reward-btn' 
         }, 
         _create: function() { 
             this.options.modalOption = this.getModalOptions(); 
             this._bind(); 
         }, 
         getModalOptions: function() { 
             /** * Modal options */ 
             var options = { 
               type: 'popup', 
               responsive: true, 
               clickableOverlay: false, 
               title: $.mage.__(''), 
               modalClass: 'popup', 
               buttons: [] 
             };  
             return options; 
         }, 
          _bind: function(){ 
             var modalOption = this.options.modalOption; 
             var modalForm = this.options.modalForm; 
             $(document).on('click', this.options.modalButton, function(){ 
                $(modalForm).modal(modalOption); 
                $(modalForm).trigger('openModal'); 
             }); 
          } 
      }); 

      return $.Carbonclick.Popup; 
   } 
);