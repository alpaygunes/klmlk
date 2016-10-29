<?php
class Pagination {
	var $button_last			= 0;
	var $button_start			= 0;
	var $button_setep			= 2;
	var $button_count			= 1;//gösteilecek sayfa linki sayısı
	var $previous_page_num 		= 0;//önceli sayafa numarası
	var $nex_page_num			= 1;//sonraki sayfa numarası
	var $item_per_page			= 10;// her sayfada gösterilecek öğe sayısı LIMIT 10
	var $total_item_count		; 	// tüm üye sayısı
	var $order_by				= 'DESC';
	var $current_page_num		= 0;// güncel sayfanın numarsı
	var $limit_start			= 0;
	var $last_page_num			;
	var $page_num				=0;
	
	
	function initialize(){
		global $CORE;
		$request				= $CORE->request;
			$this->current_page_num = isset($request['current_page_num'])?$request['current_page_num']:0;
			if(is_numeric($request['pageNo'])){
				$this->page_num		= $request['pageNo'];
			}else if($request['pageNo']=='next'){
				$this->page_num		= $this->current_page_num+1;
			}else if($request['pageNo']=='previous'){
				$this->page_num		= $this->current_page_num-1;
			}
			$this->current_page_num				=$this->page_num;
			$this->getLastPageNo();
			$this->limit_start					=$this->item_per_page*$this->current_page_num;
		//geri butonu için
		if($this->current_page_num<=0){
			$this->previous_page_num = $request['pageNo'];
		}
		// sonraki btn için
		if($this->current_page_num>=$this->last_page_num){
			$this->nex_page_num  = $request['pageNo'];
		}
	}
	
	
	function getLastPageNo(){
		$getLastPageNo				=  ceil($this->total_item_count/$this->item_per_page)-1;
		$this->last_page_num		= $getLastPageNo;
		if($this->current_page_num > $getLastPageNo){
			$this->current_page_num	=$getLastPageNo;
		}else if($this->current_page_num<0){
			$this->current_page_num	=0;
		}
		
		$this->button_start			= $this->current_page_num-$this->button_setep;
		$this->button_last			= $this->current_page_num+$this->button_setep;
		//butonların başlama ve bitiş numaraları
		if($this->button_last>$getLastPageNo){
			$this->button_last  	= $getLastPageNo;
		}
		if($this->button_start<0){
			$this->button_last		+= -$this->button_start;
			$this->button_start  	= 0;
		}
		if($this->button_last>=$getLastPageNo){
			$this->button_start		-= $this->button_setep-($getLastPageNo-$this->current_page_num);
			$this->button_last 		= $getLastPageNo;
			if($this->button_start<0){
				$this->button_start  	= 0;
			}
		}
		
		$this->button_count = $getLastPageNo;
		return $getLastPageNo;
	}
	
	function draw(){
		global $CORE;
		$this->initialize();
		$output		= '<div class="pagination">';
		$output	   .= "<div pageNo='first' 		id='first_btn'><<</div>";
		$output	   .= "<div pageNo='".($this->previous_page_num)."' 	id='previous_btn'><</div>";
		for ($i=$this->button_start;$i<=$this->button_last;$i++){
			$class='';
			if($this->current_page_num==$i){
				$class =	'class="pag_selected"';
			}
			$output	   .= "<div $class pageNo='$i' 	id='page_btn'>".($i+1)."</div>";
		}
		//$output	   .= "<div pageNo='".($this->nex_page_num)."' 		id='next_btn'>></div>";
		//$output	   .= "<div pageNo='".$this->getLastPageNo()."' 		id='last_btn'>>></div>";
		$output	   .= "<div pageNo='next' 		id='next_btn'>></div>";
		$output	   .= "<div pageNo='".$this->getLastPageNo()."' 		id='last_btn'>>></div>";
		$output	   .= '</div>';
		return 	$output;
	}
}