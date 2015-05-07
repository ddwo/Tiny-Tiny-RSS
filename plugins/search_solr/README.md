# What is this?

This is a fork of Tiny Tiny RSS with support for Solr, a search platform.

# Requirements

- Apache Solr 5.x (https://lucene.apache.org/solr/)
- Apache Solr PHP extension (https://pecl.php.net/package/solr) This is the 'php-pecl-solr2' package on Fedora 21.
- PostgreSQL JDBC driver (https://jdbc.postgresql.org/)

# Installation

1. Extract the Solr distribution somewhere
2. Navigate to the root directory and start solr with 'bin/solr start'
3. Create the Solr core to hold your tt-rss index with 'bin/solr create -e ttrss'
4. Copy the solrconfig.xml file from $SOLR_ROOT/example/example-DIH/solr/db/conf/solrconfig.xml to $SOLR_ROOT/server/solr/ttrss/conf/solrconfig.xml
5. Copy the provided schema.xml and db-data-config.xml files into that same conf directory
6. Edit the db-data-config.xml file with your SQL server details
7. Extract postgresql JDBC driver to the core lib directory ($SOLR_ROOT/server/solr/ttrss/lib, you will have to create this directory)
8. Reload the core from the admin page
9. Add "search_solr" to the list of plugins enabled in config.php in your TT-RSS installation.
10. Add the following (or something similar) to the Solr user's crontab:
```
     0  0  *  *  * curl -I "localhost:8983/solr/ttrss/dataimport?command=full-import" >/dev/null
     0  */2 * *  * curl -I "localhost:8983/solr/ttrss/dataimport?command=delta-import&clean=false" >/dev/null
```

# Usage

You can search normally and your search will be executed on the title, content, and feed_title fields, or query a specific field using "field:query" notation.

Currently the indexing schema supports:
-   title
-   feed_title
-   feed_category
-   author
-   date_updated
-   date_entered
-   keywords
-   category
-   site_url
-   link
-   marked
-   unread
-   published
-   score
-   content

More information on querying is available through the Solr documentation:
https://cwiki.apache.org/confluence/display/solr/The+Standard+Query+Parser

# Issues

The github fork for this is currently located at https://github.com/ddwo/Tiny-Tiny-RSS. **Please file any solr issues there.**
