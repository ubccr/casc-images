CASC Image Upload
=================

Operations described here assume the directory is the top level website directory (e.g., /var/www)

Helper Scripts
--------------

The dump\_raw\_images.php script will dump imges from the database into a directory with each image named using the member id and unix timestamp corresponding to the upload date (e.g., 12-1432218737.jpg). Raw images will be exported into the *image_dir*/raw directory and thumbnails for viewing will be generated and placed into the *image_dir*/180x and *image_dir*/600x directories.

Dump the current images to an archive directory (these can also be copied from the current image directory)

	php ./scripts/dump_raw_images.php -i html/images/2014

Dump images that have already been archived in the database (e.g., into *year*\_images and *year*\_member tables:

	php ./scripts/dump_raw_images.php -i html/images/2011 -y 2011

Dump a specific image (this is used by submit.php)

	php ./scripts/dump_raw_images.php -m 12 -t 1432218737 -i html/images/current;

Set Up for a New Year
---------------------

To set up a new year, archive the previous year images, set up the historical list, and clear out
the current year repository.

(1) Archive any current images in the database. Copy the images and member tables to *year*\_images and *year*\_members.

    CREATE TABLE `casc`.`2015_images` (
	  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `member_id` int(11) NOT NULL,
	  `description` mediumtext NOT NULL,
	  `name` varchar(128) NOT NULL,
	  `phone` varchar(64) NOT NULL,
	  `email` varchar(128) NOT NULL,
	  `researcher_name` varchar(128) NOT NULL,
	  `researcher_phone` varchar(64) NOT NULL,
	  `researcher_email` varchar(128) NOT NULL,
	  `researcher_institution` varchar(255) DEFAULT NULL,
	  `researcher_address` varchar(255) DEFAULT NULL,
	  `viz_name` varchar(128) DEFAULT NULL,
	  `viz_institution` varchar(255) DEFAULT NULL,
	  `compute_name` varchar(128) DEFAULT NULL,
	  `compute_system` varchar(128) DEFAULT NULL,
	  `compute_institution` varchar(255) DEFAULT NULL,
	  `date_uploaded` datetime NOT NULL,
	  `imagetype` varchar(128)  NOT  NULL,
	  `image` longblob NOT NULL,
	  PRIMARY KEY (`image_id`),
	  KEY `member_id` (`member_id`)
	) ENGINE = MyISAM
	AS SELECT * FROM `casc`.`images`;

	CREATE  TABLE  `casc`.`2015_members` (
	  `member_id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(128) NOT NULL,
	  `organization` varchar(128) DEFAULT NULL,
	  `city` varchar(64) NOT NULL,
	  `state` varchar(32) NOT NULL,
	  PRIMARY KEY (`member_id`)
	) ENGINE = MyISAM
	AS SELECT * FROM `casc`.`members`;

	TRUNACE TABLE `casc`.`images`;
	
(2) Copy images to an archive directory

	mkdir html/images/2015
	mv -a html/images/current/* html/images/2015
	
(2) Add the newly archived year to html/js/main.js

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

(4) If necessary, update the member list in the database:

	update_casc_members_from_tab_delim.php -f casc_members_20150511.tab

(5) Dump the member list from the database and update it in form.js. It is currently hardcoded in html/js/form.js.

	mysql -B -u casc -p -e "SELECT concat('[', member_id, ', ''', name, ', ', coalesce(organization, city),'''],')  FROM members;" casc
