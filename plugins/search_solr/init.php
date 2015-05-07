<?php

class Search_Solr extends Plugin {
	function about() {
		return array(1.0,
                     "Delegate searching for articles to Solr",
                     "bps",
                     true);
	}

	function init($host) {
		$host->add_hook($host::HOOK_SEARCH, $this);

		if (class_exists("SolrClient")) {
			user_error("Your PHP has a separate systemwide Solr client installed which conflicts with the client library used by tt-rss. Either remove the system library or disable Solr support.");
		}
	}

    function hook_search($search) {
        $solrServerPair = explode(":", SOLR_SERVER, 2);
        $solrOptions = array (
            'hostname' => $solrServerPair[0],
            'port' => (int) $solrServerPair[1],
            'path' => SOLR_SERVER_PATH );
        $solrClient = new SolrClient($solrOptions);

        $dismaxQuery = new SolrDisMaxQuery($search);
        $dismaxQuery->addFilterQuery("owner_uid:{$_SESSION['uid']}");
        $dismaxQuery->addField("id");
        
        $dismaxQuery
            ->addQueryField("title", 2)
            ->addQueryField("content",1.5)
            ->addQueryField("feed_title",1.5);

        $solrResult = $solrClient->query($dismaxQuery);
        $ids = array();
        $solrResultArray = $solrResult->getResponse()['response']['docs'];
        if (is_array($solrResultArray)) {
            foreach($solrResultArray as $solrObj) {
                array_push($ids, $solrObj['id']);
            }
        }
        $ids = join(",", $ids);
	 	if ($ids)
	 		return array("ref_id IN ($ids)", array());
	 	else
	 		return array("ref_id = -1", array());
    }

	function api_version() {
	 	return 2;
    }
}
?>
