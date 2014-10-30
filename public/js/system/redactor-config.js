/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */


var ImperaviRedactor = ImperaviRedactor || {};

ImperaviRedactor.baseURL = window.location.protocol+'//'+window.location.hostname+'/';
ImperaviRedactor.getBaseURL = function(url){
	if(typeof(url) == "undefined"){url = '';}
	return ImperaviRedactor.baseURL+url;
};
ImperaviRedactor.buttons = ['html','|','formatting','bold','italic','|','unorderedlist','|','alignleft','aligncenter','alignright','justify','|','image','file','video','link','|','table','|'];
//ImperaviRedactor.formatting = ['p', 'blockquote', 'h1', 'h2'];
ImperaviRedactor.buttonsCustom = {
	button_undo:{title: 'Назад',callback: function(buttonName,buttonDOM,buttonObject){this.execCommand('undo',false,false);}},
	button_redo:{title: 'Вперед',callback: function(buttonName,buttonDOM,buttonObject){this.execCommand('redo', false, false);}}
}

/********************************************************/

var imperavi_config_no_filter = {
	buttons: ImperaviRedactor.buttons,
	autoresize: false,
	minHeight: false,
	linebreaks: false,
	paragraphy: false,
	convertDivs: false,
	convertLinks: false,
	convertImageLinks: false,
	convertVideoLinks: false,

	lang: 'ru',
	plugins: ['fontsize','fullscreen'],
	imageUpload: ImperaviRedactor.getBaseURL('redactor/upload'),
	imageGetJson: ImperaviRedactor.getBaseURL('redactor/get-uploaded-images'),
	imageUploadErrorCallback: function(response){alert(response.error);},
	changeCallback: function(){},
	blurCallback: function(e){
		$(this.$element[0]).html(this.get());
	},

    clearTags: [],
    cleanStripTags: false,
    deniedTags: ['html', 'head', 'body', 'meta', 'script', 'applet'],
    removeEmptyTags: false,
    cleanSpaces: false,
    cleanup: false

};

var imperavi_config = {
	buttons: ImperaviRedactor.buttons,
	autoresize: false,
	minHeight: true,
	linebreaks: false,
	paragraphy: true,
	convertDivs: false,
	convertLinks: false,
	convertImageLinks: false,
	convertVideoLinks: false,

	lang: 'ru',
	plugins: ['fontsize','fullscreen'],
	imageUpload: ImperaviRedactor.getBaseURL('redactor/upload'),
	imageGetJson: ImperaviRedactor.getBaseURL('redactor/get-uploaded-images'),
	imageUploadErrorCallback: function(response){alert(response.error);},
	changeCallback: function(){},
	blurCallback: function(e){
		$(this.$element[0]).html(this.get());
	},
};

/********************************************************/

ImperaviRedactor.config = imperavi_config_no_filter;

if(!RedactorPlugins) var RedactorPlugins = {};
RedactorPlugins.fontsize = {
	init: function(){
		var fonts = [10, 11, 12, 14, 16, 18, 20, 24, 28, 30];
		var that = this;
		var dropdown = {};
		$.each(fonts, function(i,s){
			dropdown['s'+i] = {title: s+'px',callback: function(){that.setFontsize(s);}};
		});
		dropdown['remove'] = {title: 'Удалить размер шрифта',callback: function(){that.resetFontsize();}};
		this.buttonAdd('fontsize','Изменить размер шрифта',false,dropdown);
	},
	setFontsize: function(size){
		this.inlineSetStyle('font-size',size+'px');
	},
	resetFontsize: function(){
		this.inlineRemoveStyle('font-size');
	}
};
RedactorPlugins.fullscreen = {
	init: function(){
		this.fullscreen = false;
		this.buttonAddAfter('html', 'fullscreen','На весь экран',$.proxy(this.toggleFullscreen, this));
		//this.buttonSetRight('fullscreen');
		if(this.opts.fullscreen) this.toggleFullscreen();
	},
	toggleFullscreen: function(){
		var html;
		if(!this.fullscreen){
			this.buttonChangeIcon('fullscreen','normalscreen');
			this.buttonActive('fullscreen');
			this.fullscreen = true;
			if (this.opts.toolbarExternal){
				this.toolcss = {};
				this.boxcss = {};
				this.toolcss.width = this.$toolbar.css('width');
				this.toolcss.top = this.$toolbar.css('top');
				this.toolcss.position = this.$toolbar.css('position');
				this.boxcss.top = this.$box.css('top');
			}
			this.fsheight = this.$editor.height();
			if(this.opts.iframe) html = this.get();
			this.tmpspan = $('<span></span>');
			this.$box.addClass('redactor_box_fullscreen').after(this.tmpspan);
			$('body, html').css('overflow', 'hidden');
			$('body').prepend(this.$box);
			if(this.opts.iframe) this.fullscreenIframe(html);
			this.fullScreenResize();
			$(window).resize($.proxy(this.fullScreenResize, this));
			$(document).scrollTop(0, 0);
			this.focus();
			this.observeStart();
		}else{
			this.buttonRemoveIcon('fullscreen', 'normalscreen');
			this.buttonInactive('fullscreen');
			this.fullscreen = false;
			$(window).off('resize', $.proxy(this.fullScreenResize, this));
			$('body, html').css('overflow', '');
			this.$box.removeClass('redactor_box_fullscreen').css({ width: 'auto', height: 'auto' });
			if(this.opts.iframe) html = this.$editor.html();
			this.tmpspan.after(this.$box).remove();
			if(this.opts.iframe) this.fullscreenIframe(html);
			else this.sync();
			var height = this.fsheight;
			if(this.opts.autoresize) height = 'auto';
			if(this.opts.toolbarExternal){
				this.$box.css('top', this.boxcss.top);
				this.$toolbar.css({
					'width': this.toolcss.width,
					'top': this.toolcss.top,
					'position': this.toolcss.position
				});
			}
			if(!this.opts.iframe) this.$editor.css('height', height);
			else this.$frame.css('height', height);
			this.$editor.css('height', height);
			this.focus();
			this.observeStart();
		}
	},
	fullscreenIframe: function(html){
		this.$editor = this.$frame.contents().find('body').attr({
			'contenteditable': true,
			'dir': this.opts.direction
		});
		if(this.$editor[0]){
			this.document = this.$editor[0].ownerDocument;
			this.window = this.document.defaultView || window;
		}
		this.iframeAddCss();
		if (this.opts.fullpage) this.setFullpageOnInit(html);
		else this.set(html);
		if (this.opts.wym) this.$editor.addClass('redactor_editor_wym');
	},
	fullScreenResize: function(){
		if (!this.fullscreen) return false;
		var toolbarHeight = this.$toolbar.height();
		var pad = this.$editor.css('padding-top').replace('px', '');
		var height = $(window).height() - toolbarHeight;
		this.$box.width($(window).width() - 2).height(height + toolbarHeight);
		if(this.opts.toolbarExternal){
			this.$toolbar.css({
				'top': '0px',
				'position': 'absolute',
				'width': '100%'
			});
			this.$box.css('top', toolbarHeight + 'px');
		}
		if (!this.opts.iframe) this.$editor.height(height - (pad * 2));
		else{
			setTimeout($.proxy(function(){
				this.$frame.height(height);
			},this),1);
		}
		this.$editor.height(height);
	}
};
RedactorPlugins.textdirection = {
	init: function(){
		var that = this;
		var dropdown = {};
		dropdown['ltr'] = {title: 'По левому краю', callback: function (){that.ltrTextDirection();}};
		dropdown['rtl'] = {title: 'По правому краю', callback: function (){that.rtlTextDirection();}};
		this.buttonAdd('direction', 'Изменить выравнивание', false, dropdown);
	},
	rtlTextDirection: function(){
		this.bufferSet();
		this.blockSetAttr('dir', 'rtl');
	},
	ltrTextDirection: function(){
		this.bufferSet();
		this.blockRemoveAttr('dir');
	}
};
$(function(){
	$.Redactor.opts.langs['ru'] = {
		html: 'Код',video: 'Видео',image: 'Изображение',table: 'Таблица',link: 'Ссылка',link_insert: 'Вставить ссылку ...',
		link_edit: 'Изменить ссылку',unlink: 'Удалить ссылку',formatting: 'Форматирование',paragraph: 'Обычный текст',
		quote: 'Цитата',code: 'Код',header1: 'Заголовок 1',header2: 'Заголовок 2',header3: 'Заголовок 3',header4: 'Заголовок 4',
		header5: 'Заголовок 5',bold: 'Полужирный',italic: 'Наклонный',fontcolor: 'Цвет текста',backcolor: 'Заливка текста',
		unorderedlist: 'Обычный список',orderedlist: 'Нумерованный список',outdent: 'Уменьшить отступ',indent: 'Увеличить отступ',
		cancel: 'Отменить',insert: 'Вставить',save: 'Сохранить',_delete: 'Удалить',insert_table: 'Вставить таблицу',
		insert_row_above: 'Добавить строку сверху',insert_row_below: 'Добавить строку снизу',insert_column_left: 'Добавить столбец слева',
		insert_column_right: 'Добавить столбец справа',delete_column: 'Удалить столбец',delete_row: 'Удалить строку',
		delete_table: 'Удалить таблицу',rows: 'Строки',columns: 'Столбцы',add_head: 'Добавить заголовок',delete_head: 'Удалить заголовок',
		title: 'Подсказка',image_position: 'Обтекание текстом',none: 'Нет',left: 'Cлева',right: 'Cправа',image_web_link: 'Cсылка на изображение',
		text: 'Текст',mailto: 'Эл. почта',web: 'URL',video_html_code: 'Код видео ролика',file: 'Файл',upload: 'Загрузить',
		download: 'Скачать',choose: 'Выбрать',or_choose: 'Или выберите',drop_file_here: 'Перетащите файл сюда',align_left:'По левому краю',
		align_center: 'По центру',align_right: 'По правому краю',align_justify: 'Выровнять текст по ширине',horizontalrule: 'Горизонтальная линейка',
		fullscreen: 'Во весь экран',deleted: 'Зачеркнутый',anchor: 'Якорь',link_new_tab: 'Открывать в новой вкладке',underline: 'Подчеркнутый',
		alignment: 'Выравнивание',filename: 'Название (необязательно)',edit: 'Ред.'
	};

	$(".redactor-no-filter").redactor(imperavi_config_no_filter);
	$(".redactor").redactor(imperavi_config);
});