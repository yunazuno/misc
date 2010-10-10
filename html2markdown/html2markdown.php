#!/usr/bin/php
<?php
/**
 * Helper for Markdownify
 *
 * convert HTML to markdown using markdownify.
 * based on markdownify_cli.php
 */

require(dirname(__FILE__) .'/markdownify/markdownify_extra.php');
require('Console/CommandLine.php');


$parser = new Console_CommandLine(array(
                                        'force_options_defaults' => true,
                                        ));

//
// Console＿CommandLine用
//
$parser->addOption('links', array(
                                  'short_name' => '-l',
                                  'long_name' => '--links',
                                  'action' => 'StoreTrue',
                                  'default' => false,
                                  ));
$parser->addOption('width', array(
                                  'short_name' => '-w',
                                  'long_name' => '--width',
                                  'action' => 'StoreInt',
                                  'default' => false,
                                  ));
$parser->addOption('keeptags', array(
                                     'short_name' => '-k',
                                     'long_name' => '--keptags',
                                     'action' => 'StoreTrue',
                                     'default' => false,
                                     ));
$parser->addOption('noextra', array(
                                    'short_name' => '-n',
                                    'long_name' => '--noextra',
                                    'action' => 'StoreTrue',
                                    'default' => false,
                                    ));
$parser->addOption('in_ext', array(
                                   'short_name' => '-i',
                                   'long_name' => '--inext',
                                   'action' => 'StoreString',
                                   'default' => "html",
                                   ));
$parser->addOption('out_ext', array(
                                    'short_name' => '-o',
                                    'long_name' => '--outext',
                                    'action' => 'StoreString',
                                    'default' => "mdown",
                                    ));
$parser->addOption('out_dir', array(
                                    'short_name' => '-d',
                                    'long_name' => '--dir',
                                    'action' => 'StoreString',
                                    'default' => "output",
                                    ));
$parser->addArgument('files', array(
                                    'multiple' => true,
                                    ));

// コマンドラインオプションのパース
try
{
    $result = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
}

$links_after_each_paragraph = $result->options['links'];
$body_width = $result->options['width'];
$keep_HTML = $result->options['keeptags'];

if ($result->options['noextra']) {
    $parser = new Markdownify($links_after_each_paragraph, $body_width, $keep_HTML);
}
else
{
    $parser = new Markdownify_Extra($links_after_each_paragraph, $body_width, $keep_HTML);
}

//
// HTML-＞Markdown変換
//
while (list($key, $input_path) = each($result->args['files']))
{
    echo("Convert ${input_path}\n");

    $input = file_get_contents($input_path);
    if ($input === false)
    {
        echo "Error occured while opening ${input_path}\n";
        continue;
    }

    $parsed = $parser->parseString($input);

    $input_pathinfo = pathinfo($input_path);
    if ($input_pathinfo['extension'] == $result->options['in_ext'])
    {
        $output_path = $result->options['out_dir'] . DIRECTORY_SEPARATOR . $input_pathinfo['dirname'] . DIRECTORY_SEPARATOR . $input_pathinfo['filename'] . '.' . $result->options['out_ext'];
        $output_dir = dirname($output_path);

        if (!file_exists($output_dir))
        {
            if (mkdir($output_dir, 0775, true) === FALSE)
            {
                echo "Error occured while making directory\n";
                continue;
            }
        }

        if (file_put_contents($output_path, $parsed, LOCK_EX) === FALSE)
        {
            echo "Error occured while writing to file\n";
            continue;
        }
    }
}
