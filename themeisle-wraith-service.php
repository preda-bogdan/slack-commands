<?php
$shortopts  = "";
$shortopts .= "m:";  // Required value
$shortopts .= "s::"; // Optional value

$longopts  = array(
    "mode:",     // Required value
    "slug::",    // Optional value
);
$options = getopt( $shortopts, $longopts );
if( empty($options) ) {
    if ( isset( $_POST['mode'] ) && $_POST['mode'] == 'sitemaps' ) {
        echo "Getting https://demo.themeisle.com/wp-json/sites/all ...\n";
        $json = file_get_contents('https://demo.themeisle.com/wp-json/sites/all');
        $sites_slug_list = json_decode($json);
        foreach ($sites_slug_list as $site_slug) {
            echo "Generating " . $site_slug . ".yml\n";
            $paths_array = parse_slug_sitemaps($site_slug);
            make_yaml_file($site_slug, $paths_array);
        }
        echo "Done!\n";
    }

    if ( isset( $_POST['mode'] ) && $_POST['mode'] == 'crawl' && isset( $_POST['slug'] ) && $_POST['slug'] != '' ) {
        $site_slug = $_POST['slug'];
        echo "Generating " . $site_slug . ".yml\n";
        $paths_array = parse_slug_sitemaps($site_slug);
        make_yaml_file($site_slug, $paths_array);
        echo "Done!\n";
    }
} else {
    if ( isset( $options['m'] ) && $options['m'] == 'sitemaps' ) {
        echo "Getting https://demo.themeisle.com/wp-json/sites/all ...\n";
        $json = file_get_contents('https://demo.themeisle.com/wp-json/sites/all');
        $sites_slug_list = json_decode($json);
        foreach ($sites_slug_list as $site_slug) {
            echo "Generating " . $site_slug . ".yml\n";
            $paths_array = parse_slug_sitemaps($site_slug);
            make_yaml_file($site_slug, $paths_array);
            echo "Done!\n";
        }
    }

    if (isset($options['m']) && $options['m'] == 'crawl' && isset($options['s']) && $options['s'] != '') {
        $site_slug = $options['s'];
        echo "Generating " . $site_slug . ".yml\n";
        $paths_array = parse_slug_sitemaps($site_slug);
        make_yaml_file($site_slug, $paths_array);
        echo "Done!\n";
    }
}

function make_yaml_file( $site_slug, $paths_array ) {
    $yaml = "paths: \n";
    foreach ($paths_array as $label => $path) {
        $yaml .= "  " . $label . ": '" . $path . "'\n";
    }
    $yamlfile = fopen('configs/spyders/' . $site_slug . '_paths.yml', "w") or die("Unable to open file!");
    fwrite($yamlfile, $yaml);
    fclose($yamlfile);
}

function parse_slug_sitemaps( $site_slug ) {
    $paths_array = array();
    $paths_array['main'] = '/';

    $map_url = "https://demo.themeisle.com/" . $site_slug . "/sitemap_index.xml";
    if ( ( $response_xml_data = file_get_contents( $map_url ) ) === false ) {
        echo "Error fetching XML\n";
    } else {
        libxml_use_internal_errors( true );
        $data = simplexml_load_string( $response_xml_data );
        if ( !$data ) {
            echo "Error loading XML\n";
            foreach ( libxml_get_errors() as $error ) {
                echo "\t", $error->message;
            }
        } else {
            $sitemaps = (array) $data;
            foreach ( $sitemaps['sitemap'] as $site_map_loc ) {
                $map_crawl = (string) $site_map_loc->loc;
                if ( ( $response_xml_data_link = file_get_contents( $map_crawl ) ) === false ) {
                    echo "Error fetching XML\n";
                } else {
                    libxml_use_internal_errors( true );
                    $data = simplexml_load_string( $response_xml_data_link );
                    if ( !$data ) {
                        echo "Error loading XML\n";
                        foreach ( libxml_get_errors() as $error ) {
                            echo "\t", $error->message;
                        }
                    } else {
                        $paths = (array) $data;
                        foreach ( $paths['url'] as $path_url ) {
                            $url = (string) $path_url->loc;
                            if ( $url != '' && strlen($url) != 0 ) {
                                $formated_url = str_replace( 'https://demo.themeisle.com/' . $site_slug, '', $url );
                                if ( $formated_url != '' && strlen( $formated_url ) != 0 ) {
                                    $tag_parts = explode( '/', $formated_url );
                                    $tag_parts = array_slice( $tag_parts, 0, -1 );
                                    $tag = str_replace( '-', '_', end( $tag_parts ) );
                                    $paths_array[$tag] = $formated_url;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    return $paths_array;
}