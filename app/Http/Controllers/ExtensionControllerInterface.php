<?php

namespace App\Http\Controllers;

interface ExtensionControllerInterface{
    const CONTROLLER_NAME = 'ExtensionController';

    const HOME_INFO = 'homeInfo';
    const LIST_PAPERS = 'listPapers';
    const CATEGORY_TREE = 'getCategoryTree';
    const CATEGORY_TOP = 'getCategoryTop';
    const PAPER_BY_CATEGORY = 'getPaperCategory';
    const PAPER_RELATED = 'getRelatedPaper';
    const PAPER_COMMENTS = 'getCommentsOfPaper';
    const SEARCH = 'search';
    const PAPER_BY_WRITER = 'getPaperByWriter';
    const PAPER_MOST_VIEW = 'getPaperMostView';
    const LOGIN = 'login';
    const USER_INFO = 'getUserInfo';
    const MOST_POPULATOR_HTML = 'mostPopulator';
    const MOST_LIKE_HTML = 'likeMost';
    const MOST_TRENDING_HTML = 'trendingHtml';

    public function homeInfo();

    public function listPapers();

    public function getCategoryTree();

    public function getCategoryTop();

    /**
     * @param int $category_id
     */
    public function getPaperCategory(int $category_id);

    /**
     * @param int $paper_id
     */
    public function getRelatedPaper(int $paper_id);

    /**
     * @param int $paper_id
     */
    public function getCommentsOfPaper(int $paper_id);

    public function search();

    /**
     * @param int $writer_id
     */
    public function getPaperByWriter(int $writer_id);

    public function getPaperMostView();

    public function login();

    public function getUserInfo();

    public function mostPopulator();

    public function likeMost();

    public function trendingHtml();
}