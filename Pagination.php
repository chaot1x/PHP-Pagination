<?php

/**
 * Pagination Class
 *
 * @package   Pagination
 * @author    chaotix (chaotix@swg.lv)
 * @copyright Copyright (c) 2015
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version   1.1
 */

class Pagination {

    /**
     * Total rows 
     *
     * @var integer
     */
    public $total;

    /**
     * Array of variables for pagination
     *
     * @var array
     */
    public $instance = [];

    /**
     * Pagination templates
     *
     * @var array
     */
    public $templates = ['default' => [
            'base' => '<nav><ul class="pagination">%s</ul></nav>',
            'active' => '<li class="active"><span>%d</span></li>',
            'disabled' => '<li class="disabled"><span>...</span></li>',
            'link' => '<li><a href="%s">%d</a></li>',
            'prev' => '<li><a href="%s" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>',
            'next' => '<li><a href="%s" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>'
        ]
    ];

    /**
     * Pagination default template
     *
     * @var string
     */
    private $template = 'default';

    /**
     * Set pagination instances
     *
     * @param integer $page
     * @param integer $perpage
     * @param string $url
     */
    public function __construct($page, $perpage, $url = '?page=') {
        $page = $page ? (int)$page : 1;

        if($page < 1) {
            $page = 1;
	}

	$perpage = (int)$perpage;
        $startpoint = ($page * $perpage) - $perpage;
        $this->instance = [
            'page'       => $page,
            'limit'      => $perpage,
            'startpoint' => $startpoint,
            'limit_sql'  => "LIMIT {$startpoint}, {$perpage}",
            'url'        => $url
        ];
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
     * Make new template
     *
     * @param string $name
     * @param array $template
     */
    public function makeTemplate($name, $template) {
    	if(is_array($template)) {
            /* Set default template values if isn't set on new template */
    	    $template = $template + $this->templates['default'];
    	    $new = [$name => $template];
    	    $this->templates = array_merge($this->templates, $new);
        }
    }

    /**
     * Set pagination template
     *
     * @param string $template
     * @return void
     */
    public function template($template) {
    	$this->template = isset($this->templates[$template]) ? $template : 'default';
    	return $this;
    }

    /**
     * Parses pagination markup
     *
     * @return string
     */
    public function execute() {
        $currp = $this->instance['page'];
        $total = $this->total;
        $lastp = ceil($total/$this->instance['limit']);
        /* Small fix */
        if($currp > $lastp) {
            $currp = $lastp;
        }
        /* Don't change this */
        $adj   = 3;

        $html = '';

        if($currp != 1) {
            $html .= sprintf($this->templates[$this->template]['prev'], $this->instance['url'].($currp-1));
        }	
    
        if($lastp <= 3 + ($adj * 2)) {
            for($c = 1; $c <= $lastp; $c++) {
                if($c == $currp) {
                    $html .= sprintf($this->templates[$this->template]['active'], $c);
                } else {
                    $html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].$c, $c);
				}
			}
        } else {
            if($currp < (($adj * 2) - 1)) {
                for($c = 1; $c <= 5; $c++) {
                    if($c == $currp) {
                        $html .= sprintf($this->templates[$this->template]['active'], $c);
                    } else {
                        $html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].$c, $c);
                    }
                }

                $html .= $this->templates[$this->template]['disabled'];
				
                for($e = $lastp - 4; $e <= $lastp; $e++) {
                	$html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].$e, $e);
                }
            } elseif($lastp - (($adj * 2) - 3) > $currp && $currp > (($adj * 2) - 2)) {
                for($f = 1; $f <= 3; $f++) {
                	$html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].$f, $f);
                }

                /* Fix starting */
                if($currp == (($adj * 2) - 1)) {
                    $cstart = $currp;
                } else {
                    $cstart = $currp - 1;
                }

                /* Fix ending */
                if($currp == ($lastp - 4)) {
                    $cend = $currp;
                } else {
                    $cend = $currp + 1;
                }

                if($cstart == $currp) {
                	$html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].($currp-1), ($currp-1));
                } else {
                    $html .= $this->templates[$this->template]['disabled'];
 			    }

                for($c = $cstart; $c <= $cend; $c++) {
                    if($c == $currp) {
                        $html .= sprintf($this->templates[$this->template]['active'], $c);
                    } else {
                        $html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].$c, $c);
                    }
                }

                if($cend == $currp) {
                	$html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].($currp+1), ($currp+1));
                } else {
                    $html .= $this->templates[$this->template]['disabled'];
                }

                for($e = $lastp - 2; $e <= $lastp; $e++) {
                	$html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].$e, $e);
                }
            } else {
                for($f = 1; $f <= 5; $f++) {
                	$html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].$f, $f);
                }

                $html .= $this->templates[$this->template]['disabled'];

                for($c = $lastp - (($adj * 2) - 2); $c <= $lastp; $c++) {
                    if($c == $currp) {
                        $html .= sprintf($this->templates[$this->template]['active'], $c);
                    } else {
                    	$html .= sprintf($this->templates[$this->template]['link'], $this->instance['url'].$c, $c);
                    }
                }
            }
        }
    
        if($currp != $lastp) {
            $html .= sprintf($this->templates[$this->template]['next'], $this->instance['url'].($currp+1));
        }

        if($lastp > 1) {
        	return sprintf($this->templates[$this->template]['base'], $html);
        }
    }
}
