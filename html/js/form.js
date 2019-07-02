Ext.onReady(function(){

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    var casc_members = new Ext.data.JsonStore({
        idProperty: 'id',
        fields: ['id', 'name', 'organization', 'display'],
        url: '/memberlist.php',
        root: 'data',
        listeners: {
            load: function(store, recordList, opt) {
                // Format the display so that we show the organization first and the optional name second.
                for ( i = 0; i < recordList.length; i++ ) {
                    var display = recordList[i].data.organization;
                    if ( null != recordList[i].data.name ) {
                        display += ', ' + recordList[i].data.name;
                    }
                    recordList[i].set('display', display);
                }
            }
        }
    });

    var formPanel = new Ext.form.FormPanel({
        renderTo: 'imageform',
        id: 'image_upload',
        title: 'CASC Image Upload',
        width: 1082,
        url: '/submit.php',
        padding: 5,
        method: 'POST',
        fileUpload: true,
        frame: true,
        defaultType: 'textfield',
        labelWidth: 100,
        defaults: { width: 275 },
        monitorValid: true,
        items: [{
                xtype: 'label',
                width: 200,
                html: '<a href="/">Back</a></br>'
            },{ 
            xtype: 'fieldset',
            title: 'Image Information',
            defaultType: 'textfield',
            defaults: { width: 500 },
            autoHeight: true,
            width: 650,
            items: [{
                fieldLabel: 'Image File',
                name: 'imagefile',
                inputType: 'file',
                allowBlank: false
            },{
                xtype: 'textarea',
                fieldLabel: 'Description',
                name: 'description',
                height: 150,
                allowBlank: false
            },{
                xtype: 'combo',
                fieldLabel: 'Supporting CASC Member',
                triggerAction: 'all',
                forceSelection: true,
                allowBlank: false,
                //editable: false,
                typeAhead:true, // allow user to filter by typing beginning of name
                name: 'casc_member',
                hiddenName: 'casc_member_id',
                valueField: 'id',
                displayField: 'display',
                mode: 'remote',
                store: casc_members
            }]
        },{
            xtype: 'fieldset',
            title: 'Submitter Contact Information',
            defaultType: 'textfield',
            defaults: { width: 275 },
            autoHeight: true,
            width: 650,
            items: [{
                fieldLabel: 'Name',
                name: 'name',
                allowBlank: false
            },{
                xtype: 'compositefield',
                fieldLabel: 'Phone',
                items: [{
                    xtype: 'displayfield',
                    value: '('
                },{
                    xtype: 'textfield',
                    name: 'phone_ac',
                    regex: /[0-9]{3}/,
                    allowBlank: false,
                    autoCreate: { tag: 'input', size: 3, maxLength: 3 }
                },{
                    xtype: 'displayfield',
                    value: ')'
                },{
                    xtype: 'textfield',
                    name: 'phone_3',
                    regex: /[0-9]{3}/,
                    allowBlank: false,
                    autoCreate: { tag: 'input', size: 3, maxLength: 3 }
                },{
                    xtype: 'displayfield',
                    value: '-'
                },{
                    xtype: 'textfield',
                    name: 'phone_4',
                    regex: /[0-9]{4}/,
                    allowBlank: false,
                    autoCreate: { tag: 'input', size: 4, maxLength: 4 }
                },{
                    xtype: 'displayfield',
                    value: 'x'
                },{
                    xtype: 'textfield',
                    name: 'phone_ext',
                    regex: /[0-9]*/,
                    regexText: 'Extension must be numeric',
                    autoCreate: { tag: 'input', size: 4, maxLength: 10 }
                }]
            },{
                fieldLabel: 'Email',
                regex: /^[^@]+@[^.]+\..+/,
                regexText: 'Invalid email address',
                name: 'email'
            }]
        },{
            xtype: 'fieldset',
            title: 'Researcher Information',
            defaultType: 'textfield',
            defaults: { width: 275 },
            autoHeight: true,
            width: 650,
            items: [{
                fieldLabel: 'Name',
                name: 'researcher',
                allowBlank: false
            },{
                xtype: 'compositefield',
                fieldLabel: 'Phone',
                items: [{
                    xtype: 'displayfield',
                    value: '('
                },{
                    xtype: 'textfield',
                    name: 'r_phone_ac',
                    allowBlank: false,
                    regex: /[0-9]{3}/,
                    autoCreate: { tag: 'input', size: 3, maxLength: 3 }
                },{
                    xtype: 'displayfield',
                    value: ')'
                },{
                    xtype: 'textfield',
                    name: 'r_phone_3',
                    allowBlank: false,
                    regex: /[0-9]{3}/,
                    autoCreate: { tag: 'input', size: 3, maxLength: 3 }
                },{
                    xtype: 'displayfield',
                    value: '-'
                },{
                    xtype: 'textfield',
                    name: 'r_phone_4',
                    allowBlank: false,
                    regex: /[0-9]{4}/,
                    autoCreate: { tag: 'input', size: 4, maxLength: 4 }
                },{
                    xtype: 'displayfield',
                    value: 'x'
                },{
                    xtype: 'textfield',
                    name: 'r_phone_ext',
                    regex: /[0-9]*/,
                    autoCreate: { tag: 'input', size: 4, maxLength: 10 }
                }]
            },{
                fieldLabel: 'Email',
                regex: /^[^@]+@[^.]+\..+/,
                name: 'r_email',
                regexText: 'Invalid email address',
                allowBlank: false
            },{
                fieldLabel: 'Institution',
                name: 'r_institution',
                allowBlank:true
            },{
                xtype: 'textarea',
                fieldLabel: 'Address',
                name: 'r_address',
                width: 400,
                height: 100
            }]
        },{
            xtype: 'fieldset',
            title: 'Visualization Credits',
            defaultType: 'textfield',
            defaults: { width: 275 },
            autoHeight: true,
            width: 650,
            items: [{
                fieldLabel: 'Name',
                name: 'viz_name',
                allowBlank: true
            },{
                fieldLabel: 'Institution',
                name: 'viz_institution',
                allowBlank: true 
            }]

        },{
            xtype: 'fieldset',
            title: 'Computation Credits',
            defaultType: 'textfield',
            defaults: { width: 275 },
            autoHeight: true,
            width: 650,
            items: [{
                fieldLabel: 'Name',
                name: 'compute_name',
                allowBlank: true
            },{
                fieldLabel: 'System Name',
                name: 'compute_system',
                allowBlank: true 
            },{
                fieldLabel: 'Institution',
                name: 'compute_institution',
                allowBlank: true 
            }]

        }],
        buttons: [{
            text: 'Submit',
            formBind: true,
            handler: function()
            {
                // Since we are using a file upload we need to call
                // form.submit().  The nice thing is that action.result must
                // contain an array with a 'success' field but may have any
                // other desired data.
                formPanel.getForm().submit({
                    waitMsg: 'Uploading...',
                    success: function(form, action) {
                        Ext.Msg.alert('Success', action.result.msg);
                        form.findField('imagefile').reset();
                        form.findField('description').reset();
                    },
                    failure: function(form, action) {
                        Ext.MessageBox.alert('Status',
                                             'Error: ' + action.result.msg);
                    }
                });
            }  // handler
        },{
            text: 'Clear',
            scope: this,
            handler: function()
            {
                formPanel.getForm().reset();
            }
        }]  // buttons

    });
});
