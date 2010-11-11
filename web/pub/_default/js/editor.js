
var richEditor = {

    mode : '',

    // Getting TextArea Element By ID
    I : function(e) {
        return document.getElementById(e);
    },
    
    // Init Text Format
    initText : function(id) {
        var ta = this.I(id);
        ta.value = this.autop(ta.value);
    },
   
    // Do switch editor
    go : function(id, mode) {
    
        mode = mode || this.mode || '';

        var ed = tinyMCE.get(id) || false;

        var ta = this.I(id);

        if ('tinymce' == mode) {

            if (ed && ! ed.isHidden()) {
                return false;
            }
            
            this.mode = 'html';

            ta.value = this.autop(ta.value);
            
            if (ed) {
                ed.show();
            } else {
                tinyMCE.execCommand("mceAddControl", false, id);
            }
            
        } else {
        
            if (! ed || ed.isHidden()) {
                return false;
            }
            
            this.mode = 'tinymce';

            ed.hide();
        }
        return false;
    },
    
    // Do call back before saving
    saveCallback : function(el, text, body) {

        if (tinyMCE.activeEditor.isHidden()) {
            text = this.I(el).value;
        } else {
            text = this.preAutop(text);
        }
        
        return text;
    },
    
    // Do auto-paragraph for richeditor of tinymce
    autop : function(text) {

        var textarr = text.split(/(<[^>]*>|\[[^\]]*\])/);
        
        text = '';

        for (i = 0; i < textarr.length; i++) {
            curl = textarr[i];
            if (curl.indexOf('<') != 0 && curl.indexOf('[') != 0) {
                curl = curl.replace(new RegExp(' ', 'g'), '\u00a0');
            }
            text += curl;
        }
        
        text = text.replace(new RegExp('<br />\\s*<br />', 'gi'), "\n\n");
        text = text.replace(new RegExp("\\r\\n|\\r", 'g'), "\n");
        //text = text.replace(new RegExp("\\n\\s*\\n+", 'g'), "\n\n");
        
        text = text.replace(new RegExp("\s*\n", 'gi'), "<br />");
        
        return text;
    },
    
    // Do pre-auto-paragraph before saving
    preAutop : function(text)  {
        // Remove <p> and <br />
        text = text.replace(new RegExp('\\s*<p>', 'mgi'), '');
        text = text.replace(new RegExp('\\s*</p>\\s*', 'mgi'), '\n\n');
        text = text.replace(new RegExp('\\n\\s*\\n', 'mgi'), '\n\n');
        text = text.replace(new RegExp('\\s*<br ?/?>\\s*', 'gi'), '\n');
        
        return text;
    }
}

var tinymceInitOptions = {
    mode : "none",
    theme : "advanced",
    plugins : "safari,inlinepopups,autosave,spellchecker,paste,media,fullscreen,table",
    // Theme options
    theme_advanced_buttons1 : "bold,italic,strikethrough,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,spellchecker,fullscreen",
    theme_advanced_buttons2 : "formatselect,underline,justifyfull,forecolor,|,pastetext,pasteword,removeformat,|,media,charmap,|,outdent,indent,table,|,undo,redo",
    theme_advanced_buttons3 : "",
    theme_advanced_buttons4 : "",
    
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : "1",
    theme_advanced_resize_horizontal : "", 
    dialog_type : "modal", 
    relative_urls : "", 
    remove_script_host : "", 
    convert_urls : "", 
    apply_source_formatting : "", 
    remove_linebreaks : "1", 
    paste_convert_middot_lists : "1", 
    paste_remove_spans : "1", 
    paste_remove_styles : "1", 
    gecko_spellcheck : "1", 
    entities : "38,amp,60,lt,62,gt", 
    accessibility_focus : "1", 
    tab_focus : ":prev,:next",
    
    save_callback : "richEditor.saveCallback"
    
    //auto_resize : true,
};


