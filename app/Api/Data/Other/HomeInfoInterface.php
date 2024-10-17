<?php
namespace App\Api\Data\Other;

use App\Api\Data\AttributeInterface;

interface HomeInfoInterface extends AttributeInterface{
	const HIT = 'hit';
	const FORWARD = 'forward';
	const MOST_POPULATOR = 'mostPopulator';
	const MOST_RECENTS = 'mostRecents';
	const LIST_IMAGES = 'listImages';
	const TIME_LINE = 'timeLine';
	const SEARCH = 'search';
	const WRITERS = 'writers';
	const MAP = 'map';
	const VIDEO = 'video';

	/**
	 * @param mixed $hit
	 * @return $this
	 */
	function setHit($hit);

	/**
	 * @return
	 */
	function getHit();

	/**
	 * @param $forward
	 * @return $this
	 */
	function setForward($forward);

	/**
	 * @return 
	 */
	function getForward();

	/**
	 * @param $most_populator
	 * @return $this
	 */
	function setMostPopulator($most_populator);

	/**
	 * @return
	 */
	function getMostPopulator();

	/**
	 * @param $most_recents
	 * @return $this
	 */
	function setMostRecents($most_recents);

	/**
	 * @return
	 */
	function getMostRecents();

	/**
	 * @param $list_images
	 * @return $this
	 */
	function setListImages($list_images);

	/**
	 * @return
	 */
	function getListImages();

	/**
	 * @param $time_line
	 * @return $this
	 */
	function setTimeLine($time_line);

	/**
	 * @return
	 */
	function getTimeLine();

	/**
	 * @param string[] $search
	 * @return $this
	 */
	function setSearch($search);

	/**
	 * @return string[]
	 */
	function getSearch();

	/**
	 * @param $writers
	 * @return $this
	 */
	function setWriters($writers);

	/**
	 * @return
	 */
	function getWriters();

	/**
	 * @param $map
	 * @return $this
	 */
	function setMap($map);

	/**
	 * @return
	 */
	function getMap();

	/**
	 * @param $video
	 * @return $this
	 */
	function setVideo($video);

	/**
	 * @return
	 */
	function getVideo();
}