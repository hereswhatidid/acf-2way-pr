=== Advanced Custom Fields: Bidirectional Post Relation Field ===
Contributors: hereswhatidid
Tags: acf, post relation, bidirectional, related posts
Requires at least: 3.5
Tested up to: 4.0.0
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates an extended version of the Related Post field type that is bidirectional.

== Description ==

Creates an extended version of the Related Post field type that is bidirectional.  This new field type will create a bidirectional relationship between posts (or any post type).

This field is used in exactly the same way as the ACF [Relationship field](http://www.advancedcustomfields.com/resources/field-types/relationship/).

= Compatibility =

This ACF field type is compatible with:
* ACF 4

== Installation ==

1. Copy the `acf-2way-pr` folder into your `wp-content/plugins` folder
2. Activate the Advanced Custom Fields: Bidirectional Post Relation Field plugin via the plugins admin page
3. Create a new field via ACF and select the Advanced Custom Fields: Bidirectional Post Relation Field type
4. Please refer to the description for more info regarding the field type settings

== Changelog ==

= 1.0.3 =
* Removed support for ACF 5 temporarily

= 1.0.2 =
* Bugfix: Fixed saving error when Post Object was selected as field type

= 1.0.1 =
* Bugfix: Changed field reference from 'name' to 'key' to prevent incorrect field being grabbed when another of the same name exists
* Update: Added reference to ACF core Relationship field documentation

= 1.0.0 =
* Initial Release.