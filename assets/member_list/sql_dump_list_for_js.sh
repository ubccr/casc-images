#!/bin/sh
#
# the list is currently hard-coded into the form.js file.

mysql -p -BNe "SELECT concat('[', member_id, ', ''', name, ', ', coalesce(organization, city),'''],')  FROM members;" casc
