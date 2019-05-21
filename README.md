CASC Image Upload
=================

Operations described here assume the directory is the top level website directory (e.g., /var/www)

Notes:
- `html/imgaes` must be owned by www-data (webserver user) and writable by the application user (`chown -R www-data:casc html/images && chmod -R u+rwX,g+rwX,o+rX html/images`)

Helper Scripts
--------------

The dump\_raw\_images.php script will dump imges from the database into a directory with each image named using the image id,
member id, and unix timestamp corresponding to the upload date (e.g., 2-12-1432218737.jpg). Raw images will be exported into
the *image_dir*/raw directory and thumbnails for viewing will be generated and placed into the *image_dir*/180x and
*image_dir*/600x directories.

Dump the current images to an archive directory (these can also be copied from the current image directory)

	php ./scripts/dump_raw_images.php -d html/images/2014

Dump images that have already been archived in the database (e.g., into *year*\_images and *year*\_member tables:

	php ./scripts/dump_raw_images.php -d html/images/2011 -y 2011

Dump a specific image (this is used by submit.php)

	php ./scripts/dump_raw_images.php -i 12 -d html/images/current;

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

	TRUNCATE TABLE `casc`.`images`;
	
(2) Copy images to an archive directory. This should be done as root since the web server user
    will own some of the files/subdirectories.

	mkdir html/images/2015
	sudo mv html/images/current/* html/images/2015
	chown -R casc:casc html/images/2015
	chmod -R g+rX,o+rX html/images/2015
	
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

(2) Update the current year in html/list.php:

	// Set the current year
	$year = "2018";

(2) If necessary, update the member list in the database in assets/member_list:

	php update_casc_members_from_tab_delim.php -f casc_members_20190521.tsv -s 1 -t

	Usage: update_casc_members_from_tab_delim.php \
	[-h | --help] Display this help
	[-f | --file] Tab delimited member file
	[-s | --skip] Number of lines to skip (default 1)
	[-t | --truncate ] Truncate the members table before adding new members

(2) Dump the member list from the database and update it in form.js. It is currently hardcoded in html/js/form.js.

	mysql -B -u casc -p -e "SELECT concat('[', member_id, ', ''', name, ', ', coalesce(organization, city),'''],')  FROM members;" casc
