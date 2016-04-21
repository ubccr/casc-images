Ext.onReady(function(){

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    // Historical image submissions: files have name format YYYY_list.php
    // Add year to this ArrayStore:
    var casc_submissions_list = new Ext.data.ArrayStore({
        fields: ['value'],
        data: [
               [ '2015'],
               [ '2014'],
               [ '2013'],
               [ '2012'],
               [ '2011']
        ] 
    });

    var cb = new Ext.form.ComboBox({
                xtype: 'combo',
                fieldLabel: 'View Historical Submissions',
                triggerAction: 'all',
                forceSelection: true,
                allowBlank: true,
                editable: false,
                name: 'casc_submissions',
                hiddenName: 'value',
                valueField: 'value',
                displayField: 'value',
		value: 'Select a year',
                mode: 'local',
                store: casc_submissions_list,
		listeners:{ 	
			'select': {fn:listNavigate, scope:this}
		}
    });

    // Historical image submissions: files have name format YYYY_list.php
    function listNavigate() {
	    var year = cb.getValue();
	    window.location = String.format('/historical_list.php?year={0}', year);
    }


    var formPanel = new Ext.form.FormPanel({
        renderTo: 'landingpage',
        id: 'landing_page',
        title: 'CASC Image Competition',
        width: 1082,
        padding: 5,
        frame: true,
        defaultType: 'textfield',
        labelWidth: 200,
        defaults: { width: 100 },
        monitorValid: true,
        items: [{
            xtype: 'fieldset',
            title: 'Current Competition',
            defaultType: 'textfield',
            defaults: { width: 275 },
            autoHeight: true,
            width: 400,
            items: [{
		xtype: 'label',
    		width: 200,
    		html: '<a href="/image_submit.html">Submit an Image</a></br>'
	    },{
		xtype: 'label',
    		width: 200,
    		html: '<a href="/list.php">Current Year Submissions</a></br>'
	    }]

        },{
            xtype: 'fieldset',
            title: 'Historical Image Repository',
            defaultType: 'textfield',
            defaults: { width: 100 },
            autoHeight: true,
            width: 400,
            items: [ cb ]
        }]
    });
});
