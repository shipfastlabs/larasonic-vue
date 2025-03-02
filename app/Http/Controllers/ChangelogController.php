<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;

final class ChangelogController extends Controller
{
    public function index(): Response
    {
        $changelogPath = base_path('CHANGELOG.md');
        $markdown = File::get($changelogPath);

        // Configure the Environment
        $environment = new Environment([
            'table_of_contents' => [
                'html_class' => 'toc',
                'position' => 'top',
                'style' => 'bullet',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'normalize' => 'relative',
                'placeholder' => null,
            ],
            'heading_permalink' => [
                'html_class' => 'heading-permalink',
                'id_prefix' => 'content',
                'fragment_prefix' => 'content',
                'insert' => 'before',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'title' => 'Permalink',
                'symbol' => '#',
                'aria_hidden' => false,
            ],
        ]);

        // Add GFM extension
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new HeadingPermalinkExtension());
        $environment->addExtension(new TableOfContentsExtension());

        // Create the converter
        $converter = new CommonMarkConverter([
            'allow_unsafe_links' => false, // For security
        ], $environment);

        $html = $converter->convert($markdown)->getContent();

        return Inertia::render('Changelog/Index', [
            'changelogHtml' => $html,
        ]);
    }
} 