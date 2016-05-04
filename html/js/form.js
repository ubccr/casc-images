Ext.onReady(function(){

    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';

    // This member list needs to be manually dumped from the database. See readme.txt.

    var casc_members = new Ext.data.ArrayStore({
        idIndex: 0,
        fields: ['id', 'value'],
        data: [
[1, 'Advanced Computing Center, Arizona State University'],
[2, 'Advanced Research Computing, University of Michigan'],
[3, 'Advanced Research Computing, Virginia Tech University'],
[4, 'Advanced Research Computing Center (ARCC), University of Wyoming'],
[5, 'Alliance for Computational Science and Engineering, University of Virginia'],
[6, 'Arctic Region Supercomputing Center (ARSC), University of Alaska Fairbanks'],
[7, 'Argonne National Laboratory -, University of Chicago'],
[8, 'Berkeley Research Computing, University of California, Berkeley'],
[9, 'Center for Advanced Computing, Cornell University'],
[10, 'Center for Advanced Computing and Data Systems, University of Houston'],
[11, 'Center for Advanced Research Computing, University of New Mexico'],
[12, 'Center for Computation & Technology (CCT), Louisiana State University'],
[13, 'Center for Computation & Visualization, Brown University'],
[14, 'Center for Computational Research, University at Buffalo'],
[15, 'Center for Computational Science, Boston University'],
[16, 'Center for Computational Sciences, University of Kentucky'],
[17, 'Center for Computationally Assisted Science & Technology, North Dakota State University'],
[18, 'Center for High Performance Computing, University of Utah'],
[19, 'Center for Research Computing, University of Notre Dame'],
[20, 'Center for Simulation & Modeling, University of Pittsburgh'],
[21, 'Computing and Information Technology (CCIT), Clemson University'],
[22, 'Core Facility in Advanced Research Computing, Case Western Reserve University'],
[23, 'Discovery Informatics Institute RDI2, Rutgers University'],
[24, 'Georgia Advanced Computing Resource Center, University of Georgia'],
[25, 'Georgia Institute of Technology, Atlanta'],
[26, 'Harvard University, Boston'],
[27, 'High Performance Computing Center, Michigan State University'],
[28, 'High Performance Computing Center, Oklahoma State University'],
[29, 'High Performance Computing Center, Texas Tech University'],
[30, 'High Performance Computing Center, University of Arkansas'],
[31, 'High Performance Computing Collaboratory (HPC2), Mississippi State University'],
[32, 'High Performance Computing Facility, City University of New York'],
[33, 'Holland Computing Center, University of Nebraska'],
[34, 'Icahn School of Medicine at Mt. Sinai, Mt Sinai Medical School'],
[35, 'Indiana University, Bloomington'],
[36, 'Information Sciences Institute, University of Southern California'],
[37, 'Institute for Digital Research and Education, University of California, Los Angeles'],
[38, 'Institute for Massively Parallel Applications, George Washington University'],
[39, 'Institute for Scientific Computation, Texas A&M University'],
[40, 'Johns Hopkins University, Baltimore'],
[41, 'Ken Kennedy Institute for Information Technology (K2I), Rice University'],
[42, 'Lawrence Berkeley National Laboratory, Berkeley'],
[43, 'Maui High Performance Computing Center, University of Hawaii'],
[44, 'Michigan Technical Institute, Houghton'],
[45, 'Minnesota Supercomputing Institute, University of Minnesota'],
[46, 'Montana State University, Bozeman'],
[47, 'National Center for Atmospheric Research (NCAR), Boulder'],
[48, 'National Center for Supercomputing Applications (NCSA), University of Illinois at Urbana-Champaign'],
[49, 'National Institute for Computational Sciences (NICS), University of Tennessee'],
[50, 'National Supercomputing Center for Energy & the Environment (NSCEE), University of Nevada'],
[51, 'New York University, New York'],
[52, 'Northwestern University, Evanston'],
[53, 'Oak Ridge National Laboratory (ORNL) Center for Computational Sciences, Oak Ridge'],
[54, 'Ohio Supercomputer Center (OSC), The Ohio State University'],
[55, 'Old Dominion University, Norfolk'],
[56, 'Pittsburgh Supercomputing Center, Carnegie-Mellon University & University of Pittsburgh'],
[57, 'Princeton University, Princeton'],
[58, 'Purdue University, West Lafayette'],
[59, 'Research Computing Center, University of Arizona'],
[60, 'Research Computing Center, Columbia University'],
[61, 'Research Computing Center, University of Illinois, Chicago'],
[62, 'Research Computing Center, University of New Hampshire'],
[63, 'Research Technologies, Stony Brook University'],
[64, 'Renaissance Computing Institute (RENCI), University of North Carolina at Chapel Hill'],
[65, 'San Diego Supercomputer Center (SDSC), University of California, San Diego'],
[66, 'Scientific Computation Research Center (SCOREC), Rensselaer Polytechnic Institute'],
[67, 'Shared Research Computing Center, Florida State University'],
[68, 'Stanford University, Stanford'],
[69, 'Supercomputing Center for Education and Research, University of Oklahoma'],
[70, 'Texas Advanced Computing Center (TACC), The University of Texas at Austin'],
[71, 'The Pennsylvania State University, University Park'],
[72, 'University of Colorado Boulder, Boulder'],
[73, 'University of Connecticut, Storrs'],
[74, 'University of Florida, Gainesville'],
[75, 'University of Iowa, Iowa City'],
[76, 'University of Louisville, Louisville'],
[77, 'University of Maryland, College Park'],
[78, 'University of Massachusetts, Shrewsbury'],
[79, 'University of Miami, Miami'],
[80, 'University of North Carolina, Chapel Hill, Chapel Hill'],
[81, 'University of South Florida, Tampa'],
[82, 'University of Washington, Seattle'],
[83, 'University of Wisconsin - Madison, Madison'],
[84, 'University of Wisconsin - Milwaukee, Milwaukee'],
[85, 'Vanderbilt University, Knoxville'],
[86, 'West Virginia University, Morgantown'],
[87, 'Yale University, New Haven']
        ]
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
                displayField: 'value',
                mode: 'local',
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
