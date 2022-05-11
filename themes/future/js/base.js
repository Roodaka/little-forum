// JavaScript Document
$(document).ready(function(){
 /* TIPSY =================================================================== */
 $('a.user, a.bbc_mention').tipsy({ gravity: 'nw', opacity: 0.7, trigger: 'hover' });
 $('.search_input').tipsy({ gravity: 's', opacity: 0.9, trigger: 'hover' });
 $('#leftbar .left_panel ul.topics li a').tipsy({ gravity: 'w', opacity: 0.8, trigger: 'hover' });
 $('.toolsbar a').tipsy({ gravity: 'nw', opacity: 0.9, trigger: 'hover' });
 /* TIPSY =================================================================== */

  /* Registro y Login por Ajax */
  $('#call_login').click(function(e) { e.preventDefault(); $('#window_login').modal({overlayClose:true, opacity:20, overlayCss:{backgroundColor:'#484848'}});});
  $('#call_register').click(function(e) { e.preventDefault(); $('#window_register').modal({overlayClose:true, opacity:20, overlayCss:{backgroundColor:'#484848'}});});
 /*
  * Funcionamiento del MarkItUp cortesía de AresLepra - Marifa.org
  */
  $('#content').markItUp({ previewParserPath: '', onShiftEnter: { keepDefault: false, openWith:'\n\n'}, markupSet:[] });

  /* LISTADO ETIQUETAS */
  $('.toolsbar a.align_left').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Align Left', openWith:'[align=left]', closeWith: '[/align]'}); });
  $('.toolsbar a.align_center').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Align center', openWith:'[align=center]', closeWith: '[/align]'}); });
  $('.toolsbar a.align_right').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Align Right', openWith:'[align=right]', closeWith: '[/align]'}); });
  $('.toolsbar a.align_justify').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Align justify', openWith:'[align=justify]', closeWith: '[/align]'}); });

  $('.toolsbar a.bold').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), openWith:'[b]', closeWith: '[/b]'}); });
  $('.toolsbar a.italic').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Italic', key: 'I', openWith:'[i]', closeWith: '[/i]'}); });
  $('.toolsbar a.underline').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Underline', key: 'U', openWith:'[u]', closeWith: '[/u]'}); });
  $('.toolsbar a.strike').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Srike', key: 'S', openWith:'[s]', closeWith: '[/s]'}); });

  $('.toolsbar a.list').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Bulleted list', openWith: '[list]\n', closeWith: '\n[/list]'}); });
  $('.toolsbar a.list-element').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Bulleted list', openWith: '[li]', closeWith: '[/li]'}); });
  $('.toolsbar a.numeric-list').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Numeric list', openWith: '[olist]\n', closeWith: '\n[/olist]'}); });

  $('.toolsbar a.image').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Picture', key: 'P', replaceWith: '[img][![Url]!][/img]'}); });
  $('.toolsbar a.code').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Code', openWith: '[code]', closeWith: '[/code]'}); });
  $('.toolsbar a.link').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Link', key: 'L', openWith: '[url=[![Url]!]]', closeWith: '[/url]', placeHolder: 'Link'}); });
  $('.toolsbar a.quote').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Quote', openWith: '[quote]', closeWith: '[/quote]'}); });
  $('.toolsbar a.mention').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Mention', openWith: '[user]', closeWith: '[/user]'}); });
  $('.toolsbar a.spoiler').click(function(e) { e.preventDefault(); $.markItUp({ target: $('#content'), name: 'Spoiler', openWith: '[spoiler]', closeWith: '[/spoiler]'}); });
  /* CITAR COMENTARIOS DE OTROS USUARIOS */
  $('.quote_button').click(function(e) { e.preventDefault(); $.markItUp({openWith: '[quote='+$(this).data('nick')+']'+$('#' + $(this).data('cnt')).text()+'[/quote]'}); });

 });