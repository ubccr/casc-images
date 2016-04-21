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
[5, 'Advanced Computing Services and Engagement (UVACSE), University of Virginia'],
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
[28, 'High Performance Computing Center, Texas Tech University'],
[29, 'High Performance Computing Center, University of Arkansas'],
[30, 'High Performance Computing Collaboratory (HPC2), Mississippi State University'],
[31, 'High Performance Computing Facility, City University of New York'],
[32, 'Holland Computing Center, University of Nebraska'],
[33, 'Icahn School of Medicine at Mt. Sinai, Mt Sinai Medical School'],
[34, 'Indiana University, Bloomington'],
[35, 'Information Sciences Institute, University of Southern California'],
[36, 'Institute for Digital Research and Education, University of California, Los Angeles'],
[37, 'Institute for Massively Parallel Applications, George Washington University'],
[38, 'Institute for Scientific Computation, Texas A&M University'],
[39, 'Johns Hopkins University, Baltimore'],
[40, 'Ken Kennedy Institute for Information Technology (K2I), Rice University'],
[41, 'Lawrence Berkeley National Laboratory, Berkeley'],
[42, 'Maui High Performance Computing Center, University of Hawaii'],
[43, 'Michigan Technical Institute, Houghton'],
[44, 'Minnesota Supercomputing Institute, University of Minnesota'],
[45, 'National Center for Atmospheric Research (NCAR), Boulder'],
[46, 'National Center for Supercomputing Applications (NCSA), University of Illinois at Urbana-Champaign'],
[47, 'National Institute for Computational Sciences (NICS), University of Tennessee'],
[48, 'National Supercomputing Center for Energy & the Environment (NSCEE), University of Nevada'],
[49, 'New York University, New York'],
[50, 'Northwestern University, Evanston'],
[51, 'Oak Ridge National Laboratory (ORNL) Center for Computational Sciences, Oak Ridge'],
[52, 'Ohio Supercomputer Center (OSC), The Ohio State University'],
[53, 'Pittsburgh Supercomputing Center, Carnegie-Mellon University & University of Pittsburgh'],
[54, 'Princeton University, Princeton'],
[55, 'Purdue University, West Lafayette'],
[56, 'Research Computing Center, University of Arizona'],
[57, 'Research Computing Center, Columbia University'],
[58, 'Research Technologies, Stony Brook University'],
[59, 'Renaissance Computing Institute (RENCI), University of North Carolina at Chapel Hill'],
[60, 'San Diego Supercomputer Center (SDSC), University of California, San Diego'],
[61, 'Scientific Computation Research Center (SCOREC), Rensselaer Polytechnic Institute'],
[62, 'Shared Research Computing Center, Florida State University'],
[63, 'Stanford University, Stanford'],
[64, 'Supercomputing Center for Education and Research, University of Oklahoma'],
[65, 'Texas Advanced Computing Center (TACC), The University of Texas at Austin'],
[66, 'The Pennsylvania State University, University Park'],
[67, 'University of Colorado Boulder, Boulder'],
[68, 'University of Connecticut, Storrs'],
[69, 'University of Florida, Gainesville'],
[70, 'University of Iowa, Iowa City'],
[71, 'University of Louisville, Louisville'],
[72, 'University of Maryland, College Park'],
[73, 'University of Massachusetts, Shrewsbury'],
[74, 'University of Miami, Miami'],
[75, 'University of North Carolina, Chapel Hill, Chapel Hill'],
[76, 'University of South Florida, Tampa'],
[77, 'University of Washington, Seattle'],
[78, 'University of Wisconsin - Madison, Madison'],
[79, 'University of Wisconsin - Milwaukee, Milwaukee'],
[80, 'Vanderbilt University, Knoxville'],
[81, 'West Virginia University, Morgantown'],
[82, 'Yale University, New Haven']
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
