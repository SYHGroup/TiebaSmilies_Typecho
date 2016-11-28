<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * 贴吧评论表情与贴图插件 原作者<a href="http://www.jzwalk.com/archives/net/smilies-for-typecho">羽中</a>
 * 
 * @package TiebaSmilie_Typecho
 * @author simonsmh
 * @version 1.0.0
 * @dependence 13.12.12-*
 * @link https://github.com/simonsmh/TiebaSmilie_Typecho/
 */
class Smilies_Plugin implements Typecho_Plugin_Interface
{
	/**
	 * 激活插件方法,如果激活失败,直接抛出异常
	 * 
	 * @access public
	 * @return void
	 * @throws Typecho_Plugin_Exception
	 */
	public static function activate()
	{
		Typecho_Plugin::factory('Widget_Abstract_Comments')->contentEx = array('Smilies_Plugin','showsmilies');
		Typecho_Plugin::factory('Widget_Archive')->footer = array('Smilies_Plugin','insertjs');
	}

	/**
	 * 禁用插件方法,如果禁用失败,直接抛出异常
	 * 
	 * @static
	 * @access public
	 * @return void
	 * @throws Typecho_Plugin_Exception
	 */
	public static function deactivate(){}

	/**
	 * 获取插件配置面板
	 * 
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form 配置面板
	 * @return void
	 */
	public static function config(Typecho_Widget_Helper_Form $form)
	{
?>
	<div style="color:#999;font-size:0.92857em;font-weight:bold;"><p><?php _e('在comments.php适当位置插入代码%s即可. ','<span style="color:#467B96;">&lt;?php Smilies_Plugin::output(); ?&gt;</span>'); ?><br/>
	<?php _e('注意评论框id须为"textarea", 例: %s','&lt;textarea name="text" id="<span style="color:#E47E00;">textarea</span>"...'); ?></p></div>
	<script type="text/javascript" src="<?php Helper::options()->adminUrl('js/jquery.js'); ?>"></script>
	<script type="text/javascript">
	$(function(){
		var jqmode1 = $("#jqmode-1"),
			jqmode0 = $("#jqmode-0");
		if(jqmode1.is(":checked")){
			return false;
		}
		else{
			var jqhost = $("#typecho-option-item-jqhost-3");
			jqhost.attr("style","color:#999")
			.find("input").attr("disabled","disabled");
			jqmode1.click(function(){
				jqhost.removeAttr("style")
				.find("input").removeAttr("disabled");
			});
			jqmode0.click(function(){
				jqhost.attr("style","color:#999")
				.find("input").attr("disabled","disabled");
			});
		}
	});
	</script>
<?php
		$smiliesset= new Typecho_Widget_Helper_Form_Element_Select('smiliesset',
			self::parsefolders(),'tieba-smilies',_t('表情风格'),_t('插件目录下若新增表情风格文件夹可刷新本页在下拉菜单中选择. <br/>注意图片名须参考其他文件夹保持一致, 如icon_cry.gif对应哭泣表情等'));
		$form->addInput($smiliesset);

		$allowpop = new Typecho_Widget_Helper_Form_Element_Radio('allowpop',
			array(0=>_t('关闭'),1=>_t('开启')),1,_t('弹窗效果'));
		$form->addInput($allowpop);

		$jqmode = new Typecho_Widget_Helper_Form_Element_Radio('jqmode',
			array(0=>_t('原生js'),1=>_t('jQuery')),0,_t('代码模式'));
		$form->addInput($jqmode);

		$jqhost = new Typecho_Widget_Helper_Form_Element_Text('jqhost',
		NULL,'http://code.jquery.com/jquery-2.2.1.min.js',_t('jQuery源'));
		$form->addInput($jqhost);
	}

	/**
	 * 个人用户的配置面板
	 * 
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form
	 * @return void
	 */
	public static function personalConfig(Typecho_Widget_Helper_Form $form){}

	/**
	 * 扫描表情文件夹
	 *
	 * @access private
	 * @return array
	 */
	private static function parsefolders()
	{
		$results = glob(__TYPECHO_ROOT_DIR__.__TYPECHO_PLUGIN_DIR__.'/Smilies/*',GLOB_ONLYDIR);

		foreach ($results as $result) {
			$name = iconv('gbk','utf-8',
				str_replace(__TYPECHO_ROOT_DIR__.__TYPECHO_PLUGIN_DIR__.'/Smilies/','',$result)
				);
			$folders[$name]= $name;
		}

		return $folders;
	}

	/**
	 * 整理表情数据
	 *
	 * @access private
	 * @return array
	 */
	private static function parsesmilies()
	{
		$options = Helper::options();
		$settings = $options->plugin('Smilies');

		$smiliestrans = array(
			':image_emoticon1.png:'		=> 'image_emoticon1.png',
			':image_emoticon2.png:'		=> 'image_emoticon2.png',
			':image_emoticon3.png:'		=> 'image_emoticon3.png',
			':image_emoticon4.png:'		=> 'image_emoticon4.png',
			':image_emoticon5.png:'		=> 'image_emoticon5.png',
			':image_emoticon6.png:'		=> 'image_emoticon6.png',
			':image_emoticon7.png:'		=> 'image_emoticon7.png',
			':image_emoticon8.png:'		=> 'image_emoticon8.png',
			':image_emoticon9.png:'		=> 'image_emoticon9.png',
			':image_emoticon10.png:'		=> 'image_emoticon10.png',
			':image_emoticon11.png:'		=> 'image_emoticon11.png',
			':image_emoticon12.png:'		=> 'image_emoticon12.png',
			':image_emoticon13.png:'		=> 'image_emoticon13.png',
			':image_emoticon14.png:'		=> 'image_emoticon14.png',
			':image_emoticon15.png:'		=> 'image_emoticon15.png',
			':image_emoticon16.png:'		=> 'image_emoticon16.png',
			':image_emoticon17.png:'		=> 'image_emoticon17.png',
			':image_emoticon18.png:'		=> 'image_emoticon18.png',
			':image_emoticon19.png:'		=> 'image_emoticon19.png',
			':image_emoticon20.png:'		=> 'image_emoticon20.png',
			':image_emoticon21.png:'		=> 'image_emoticon21.png',
			':image_emoticon22.png:'		=> 'image_emoticon22.png',
			':image_emoticon23.png:'		=> 'image_emoticon23.png',
			':image_emoticon24.png:'		=> 'image_emoticon24.png',
			':image_emoticon25.png:'		=> 'image_emoticon25.png',
			':image_emoticon26.png:'		=> 'image_emoticon26.png',
			':image_emoticon27.png:'		=> 'image_emoticon27.png',
			':image_emoticon28.png:'		=> 'image_emoticon28.png',
			':image_emoticon29.png:'		=> 'image_emoticon29.png',
			':image_emoticon30.png:'		=> 'image_emoticon30.png',
			':image_emoticon31.png:'		=> 'image_emoticon31.png',
			':image_emoticon32.png:'		=> 'image_emoticon32.png',
			':image_emoticon33.png:'		=> 'image_emoticon33.png',
			':image_emoticon34.png:'		=> 'image_emoticon34.png',
			':image_emoticon35.png:'		=> 'image_emoticon35.png',
			':image_emoticon36.png:'		=> 'image_emoticon36.png',
			':image_emoticon37.png:'		=> 'image_emoticon37.png',
			':image_emoticon38.png:'		=> 'image_emoticon38.png',
			':image_emoticon39.png:'		=> 'image_emoticon39.png',
			':image_emoticon40.png:'		=> 'image_emoticon40.png',
			':image_emoticon41.png:'		=> 'image_emoticon41.png',
			':image_emoticon42.png:'		=> 'image_emoticon42.png',
			':image_emoticon43.png:'		=> 'image_emoticon43.png',
			':image_emoticon44.png:'		=> 'image_emoticon44.png',
			':image_emoticon45.png:'		=> 'image_emoticon45.png',
			':image_emoticon46.png:'		=> 'image_emoticon46.png',
			':image_emoticon47.png:'		=> 'image_emoticon47.png',
			':image_emoticon48.png:'		=> 'image_emoticon48.png',
			':image_emoticon49.png:'		=> 'image_emoticon49.png',
			':image_emoticon50.png:'		=> 'image_emoticon50.png',

		);

		$smiliesurl = Typecho_Common::url('Smilies/'.urlencode($settings->smiliesset).'/',$options->pluginUrl);
		$smiled = array();

		foreach ($smiliestrans as $tag=>$grin) {
			$smilies = '<img src="'.$smiliesurl.'image_emoticon25.png" alt="选择表情"/>';

			if (!in_array($grin,$smiled)) {
				$smiled[] = $grin;
				$smiliesicon[] = '<span onclick="Smilies.grin(\''.$tag.'\');" style="cursor:pointer;" data-tag=" '.$tag.' "><img style="margin:2px;" src="'.$smiliesurl.$grin.'" alt="'.$grin.'"/></span>';
			}

			$smiliestag[] = $tag;

			$smiliesimg[] = '<img class="smilies" src="'.$smiliesurl.$grin.'" alt="'.$grin.'"/>';
		}

		return array($smilies,$smiliesicon,$smiliestag,$smiliesimg);
	}

	/**
	 * 解析表情图片
	 *
	 * @access public
	 * @param string $text 评论内容
	 * @return string
	 */
	public static function showsmilies($text,$widget,$lastResult)
	{
		$text = empty($lastResult)?$text:$lastResult;

		Helper::options()->commentsHTMLTagAllowed .= '<img src="" alt=""/>';

		$arrays = self::parsesmilies();

		if ($widget instanceof Widget_Abstract_Comments) {
			return str_replace($arrays[2],$arrays[3],$text);
		} else {
			return $text;
		}

	}

	/**
	 * 输出表情图片
	 *
	 * @access public
	 * @return void
	 */
	public static function output()
	{
		$options = Helper::options();
		$settings = $options->plugin('Smilies');

		$smilies = '';
		$shadow = 'box-shadow: rgba(190,190,190,1) 1px 3px 15px';
		$border = 'border-radius: 11px';
		$arrays = self::parsesmilies();

		$icons = array_unique($arrays[1]);
		foreach ($icons as $icon) {
			$smilies .= $icon;
		}

		$smiliesdisplay = ($settings->allowpop)?
			'display:none;position:absolute;z-index:99;width:240px;margin-top:-70px;padding:5px;background:#fff;border:1px solid #bbb;-moz-'.$shadow.';-webkit-'.$shadow.';-khtml-'.$shadow.';'.$shadow.';-moz-'.$border.';-webkit-'.$border.';-khtml-'.$border.';'.$border.';':
			'display:block;';

		$output = '<div id="smiliesbox" style="'.$smiliesdisplay.'">';
		$output .= $smilies;
		$output .= '</div>';

		if ($settings->allowpop)
			$output .= '<span onclick="Smilies.showBox();" style="cursor:pointer;" id="smiliesbutton" title="选择表情">'.$arrays[0].'</span>';

		echo $output;
	}

	/**
	 * 输出js脚本
	 *
	 * @access public
	 * @return void
	 */
	public static function insertjs($widget)
	{
		$settings = Helper::options()->plugin('Smilies');

		if ($settings->jqmode) {
			//jquery模式
			$js = '<script type="text/javascript" src="'. Helper::options()->plugin('TiebaSmilie_Typecho')->jqhost .'"></script>';
			$js .= '
<script type="text/javascript">
$(function() {
	var box = $("#smiliesbox"),
		button = $("#smiliesbutton"),
		a = null;
	box.mouseover(function(){
		clearTimeout(a);
		a = null;
	});';
			if ($settings->allowpop)
			$js .= '
	box.mouseleave(function(){
		button.mouseout();
	});';
			$js .= '
	box.find("span").click(function() {
		var b = $(this).attr("data-tag");
		$("#textarea").insert(b);
		box.fadeOut("fast");
	});
	button.on({
		click:function(){
			box.fadeIn();
		},
		mouseover:function(){
			box.fadeIn();
		},
		mouseout:function(){
			a = setTimeout(function(){
				box.fadeOut();
				clearTimeout(a);
				a = null
			},200);
		}
	});
$.fn.extend({
	"insert": function(myValue) {
		var $t = $(this)[0];
		if (document.selection) {
			this.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
			this.focus()
		} else if ($t.selectionStart || $t.selectionStart == "0") {
			var startPos = $t.selectionStart;
			var endPos = $t.selectionEnd;
			var scrollTop = $t.scrollTop;
			$t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
			this.focus();
			$t.selectionStart = startPos + myValue.length;
			$t.selectionEnd = startPos + myValue.length;
			$t.scrollTop = scrollTop
		} else {
			this.value += myValue;
			this.focus()
		}
	}
}) 
});
</script>
		';
		//原生js模式
		} else {
		$js = "
<script type=\"text/javascript\">
//<![CDATA[
Smilies = {
	dom : function(id) {
		return document.getElementById(id);
	},
	showBox : function () {
		this.dom('smiliesbox').style.display = 'block';
		document.onclick = function() {
			this.closeBox();
		}
	},
	closeBox : function () {
		this.dom('smiliesbox').style.display = 'none';
	},
	grin : function (tag) {
		tag = ' ' + tag + ' '; myField = this.dom('textarea');
		document.selection ? (myField.focus(),sel = document.selection.createRange(),sel.text = tag,myField.focus()) : this.insertTag(tag);
	},
	insertTag : function (tag) {
		myField = Smilies.dom('textarea');
		myField.selectionStart || myField.selectionStart == '0' ? (
			startPos = myField.selectionStart,
			endPos = myField.selectionEnd,
			cursorPos = startPos,
			myField.value = myField.value.substring(0,startPos)
				+ tag
				+ myField.value.substring(endPos,myField.value.length),
			cursorPos += tag.length,
			myField.focus(),
			myField.selectionStart = cursorPos,
			myField.selectionEnd = cursorPos
		) : (
			myField.value += tag,
			myField.focus()
		);";
		if ($settings->allowpop)
		$js .= "
		this.closeBox();";
		$js .= "
	}
}
//]]>
</script>";
		}

		if ($widget->is('single')) {
			echo $js;
		}

	}

}
