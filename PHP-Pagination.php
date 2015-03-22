<?php

/**
 * Pagination Class
 *
 * @package   Pagination
 * @author    chaotix (elvijs.obuhovs@gmail.com)
 * @copyright Copyright (c) 2015
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version   1.0
 */

class Pagination {

	/**
	 * Total rows 
	 *
	 * @var string
	 */
	private $total;
	/**
	 * Array of variables for pagination
	 *
	 * @var array
	 */
	public $instance = [];

	/**
	 * Prepare pagination
	 *
	 * @param integer $page
	 * @param integer $perpage
	 * @param string $url
	 * @return array
     */
	public function prepare($page, $perpage, $url = '?page=') {
		$page = (int) $page ? $page : 1;
		if($page < 1)
			$page = 1;

		$startpoint = ($page * $perpage) - $perpage;
		$this->instance = [
			'page' 		 => $page,
			'limit'	     => $perpage,
			'startpoint' => $startpoint,
			'limit_sql'  => "LIMIT {$startpoint}, {$perpage}",
			'url'        => $url
		];
		return $this->instance;
	}

	/**
	 * Set total rows
	 *
	 * @param integer $total
	 * @return void
     */
	public function total($total) {
		$this->total = (int)$total;
		return $this;
	}

	/**
	 * Parses pagination markup
	 *
	 * @return void
	 */
	public function execute() {
		$currp = $this->instance['page'];
		$total = $this->total;
		$lastp = ceil($total/$this->instance['limit']);
		//-- Don't change this
		$adj   = 3;


		$html = '';

		if( $lastp > 1 ) {
			$html .= '<nav>
				<ul class="pagination">';

			if($currp != 1) {
				$html .= '<li>
					<a href="'.$this->instance['url'].($currp-1).'" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					</a>
				</li>';
			}	

			if($lastp <= 3 + ($adj * 2)) {
				for($c = 1; $c <= $lastp; $c++) {
					if($c == $currp) {
						$html .= '<li class="active">
							<span>'.$c.'</span>
						</li>';
					} else {
						$html .= '<li>
							<a href="'.$this->instance['url'].$c.'">'.$c.'</a>
						</li>';
					}
				}
			} else {
				if($currp < (($adj * 2) - 1)) {
					for($c = 1; $c <= 5; $c++) {
						if($c == $currp) {
							$html .= '<li class="active">
								<span>'.$c.'</span>
							</li>';
						} else {
							$html .= '<li>
								<a href="'.$this->instance['url'].$c.'">'.$c.'</a>
							</li>';
						}
					}
					$html .= '<li class="disabled">
						<span>...</span>
					</li>';
					for($e = $lastp - 4; $e <= $lastp; $e++) {
						$html .= '<li>
							<a href="'.$this->instance['url'].$e.'">'.$e.'</a>
						</li>';
					}
				} elseif($lastp - (($adj * 2) - 3) > $currp && $currp > (($adj * 2) - 2)) {
					for($f = 1; $f <= 3; $f++) {
						$html .= '<li>
							<a href="'.$this->instance['url'].$f.'">'.$f.'</a>
						</li>';
					}
					//-- Fix starting
					if($currp == (($adj * 2) - 1)) {
						$cstart = $currp;
					} else {
						$cstart = $currp - 1;
					}
					//-- Fix ending
					if($currp == ($lastp - 4)) {
						$cend = $currp;
					} else {
						$cend = $currp + 1;
					}

					if($cstart == $currp) {
						$html .= '<li>
							<a href="'.$this->instance['url'].($currp - 1).'">'.($currp - 1).'</a>
						</li>';
					} else {
						$html .= '<li class="disabled">
							<span>...</span>
						</li>';
					}
					for($c = $cstart; $c <= $cend; $c++) {
						if($c == $currp) {
							$html .= '<li class="active">
								<span>'.$c.'</span>
							</li>';
						} else {
							$html .= '<li>
								<a href="'.$this->instance['url'].$c.'">'.$c.'</a>
							</li>';
						}
					}
					if($cend == $currp) {
						$html .= '<li>
							<a href="'.$this->instance['url'].($currp + 1).'">'.($currp + 1).'</a>
						</li>';
					} else {
						$html .= '<li class="disabled">
							<span>...</span>
						</li>';
					}
					for($e = $lastp - 2; $e <= $lastp; $e++) {
						$html .= '<li>
							<a href="'.$this->instance['url'].$e.'">'.$e.'</a>
						</li>';
					}
				} else {
					for($f = 1; $f <= 5; $f++) {
						$html .= '<li>
							<a href="'.$this->instance['url'].$f.'">'.$f.'</a>
						</li>';
					}
					$html .= '<li class="disabled">
						<span>...</span>
					</li>';
					for($c = $lastp - (($adj * 2) - 2); $c <= $lastp; $c++) {
						if($c == $currp) {
							$html .= '<li class="active">
								<span>'.$c.'</span>
							</li>';
						} else {
							$html .= '<li>
								<a href="'.$this->instance['url'].$c.'">'.$c.'</a>
							</li>';
						}
					}
				}
			}

			if($currp != $lastp) {
				$html .= '<li>
					<a href="'.$this->instance['url'].($currp+1).'" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					</a>
				</li>';
			}	

			$html .= '</ul>
			</nav>';
		}

		return $html;
	}

}
