<?php
/*
Plugin Name: Simple Theme Malware Scanner
Description: Scans theme files for suspicious PHP functions and reports results.
Version: 1.0
Author: Robert Konsavage
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Simple_Theme_Malware_Scanner {
    private $suspicious_functions = [
        'eval',
        'base64_decode',
        'shell_exec',
        'exec',
        'passthru',
        'system',
        'preg_replace', // with /e modifier (older PHP versions)
        'assert',
        'create_function',
        'popen',
    ];

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_page']);
    }

    public function add_admin_page() {
        add_theme_page(
            'Theme Malware Scanner',
            'Malware Scanner',
            'manage_options',
            'theme-malware-scanner',
            [$this, 'render_admin_page']
        );
    }

    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        echo '<div class="wrap"><h1>Theme Malware Scanner</h1>';

        if (isset($_POST['scan'])) {
            $results = $this->scan_theme();
            echo '<h2>Scan Results</h2>';
            if (empty($results)) {
                echo '<p>No suspicious code found.</p>';
            } else {
                echo '<table style="width:100%;border-collapse:collapse;">';
                echo '<thead><tr><th style="border:1px solid #ddd;padding:8px;">File</th><th style="border:1px solid #ddd;padding:8px;">Suspicious Function(s)</th></tr></thead><tbody>';
                foreach ($results as $file => $functions) {
                    echo '<tr>';
                    echo '<td style="border:1px solid #ddd;padding:8px;">' . esc_html($file) . '</td>';
                    echo '<td style="border:1px solid #ddd;padding:8px;">' . esc_html(implode(', ', $functions)) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            }
        }

        echo '<form method="post">';
        echo '<input type="submit" name="scan" class="button button-primary" value="Scan Theme Files">';
        echo '</form></div>';
    }

    private function scan_theme() {
        $theme_dir = get_template_directory();
        $files = $this->get_php_files($theme_dir);
        $results = [];

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $found = [];

            foreach ($this->suspicious_functions as $func) {
                // For preg_replace with /e modifier, check differently
                if ($func === 'preg_replace') {
                    if (preg_match('/preg_replace\s*\(.*?\/e.*?\)/is', $content)) {
                        $found[] = 'preg_replace (with /e modifier)';
                    }
                } else {
                    // Use word boundaries to reduce false positives
                    if (preg_match('/\b' . preg_quote($func, '/') . '\b/i', $content)) {
                        $found[] = $func;
                    }
                }
            }

            if (!empty($found)) {
                // Save relative path
                $relative_path = str_replace(ABSPATH, '', $file);
                $results[$relative_path] = $found;
            }
        }
        return $results;
    }

    private function get_php_files($dir) {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $files = [];

        foreach ($rii as $file) {
            if ($file->isDir()){
                continue;
            }

            if (pathinfo($file->getPathname(), PATHINFO_EXTENSION) === 'php') {
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }
}

new Simple_Theme_Malware_Scanner();
