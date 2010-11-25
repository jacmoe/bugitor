// -------------------------------------------------------------------
// markItUp!
// -------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// -------------------------------------------------------------------
// Textile tags example
// http://en.wikipedia.org/wiki/Textile_(markup_language)
// http://www.textism.com/
// -------------------------------------------------------------------
// Feel free to add more tags
// -------------------------------------------------------------------
mySettings = {
	previewParserPath:	'~/classTextile.php', // path to your Textile parser
	onShiftEnter:		{keepDefault:false, replaceWith:'\n\n'},
	markupSet: [
		{name:'Heading 1', key:'1', openWith:'h1(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
		{name:'Heading 2', key:'2', openWith:'h2(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
		{name:'Heading 3', key:'3', openWith:'h3(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
		{name:'Heading 4', key:'4', openWith:'h4(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
		{name:'Heading 5', key:'5', openWith:'h5(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
		{name:'Heading 6', key:'6', openWith:'h6(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
		{name:'Paragraph', key:'P', openWith:'p(!(([![Class]!]))!). '},
		{separator:'---------------' },
		{name:'Bold', key:'B', closeWith:'*', openWith:'*'},
		{name:'Italic', key:'I', closeWith:'_', openWith:'_'},
		{name:'Stroke through', key:'S', closeWith:'-', openWith:'-'},
		{separator:'---------------' },
		{name:'Bulleted list', openWith:'(!(* |!|*)!)'},
		{name:'Numeric list', openWith:'(!(# |!|#)!)'}, 
		{separator:'---------------' },
		{name:'Picture', replaceWith:'![![Source:!:http://]!]([![Alternative text]!])!'}, 
		{name:'Link', openWith:'"', closeWith:'([![Title]!])":[![Link:!:http://]!]', placeHolder:'Your text to link here...' },
		{separator:'---------------' },
		{name:'Quotes', openWith:'bq(!(([![Class]!]))!). '},
		{name:'Code', openWith:'@', closeWith:'@'},
		{separator:'---------------' },
		{name:'Preview', call:'preview', className:'preview'},
		{name: 'Smilies1', className: "smilies1",
                    dropMenu: [
                            {name:'Smile',	replaceWith:':smile:', className:"smile" },
                            {name:'Wink',	replaceWith:':wink:', className:"wink" },
                            {name:'Big Grin',	replaceWith:':big_grin:', className:"big_grin" },
                            {name:'Cheesy',	replaceWith:':cheesy_grin:', className:"cheesy" },
                            {name:'Confused',	replaceWith:':confused:', className:"confused" },
                            {name:'Cool',	replaceWith:':cool:', className:"cool" },
                            {name:'Cry',	replaceWith:':cry:', className:"cry" },
                            {name:'Sad',	replaceWith:':sad:', className:"sad" },
                            {name:'Surprised',	replaceWith:':surprised:', className:"surprised" },
                ] },
		{name: 'Smilies2', className: "smilies2",
                    dropMenu: [
                            {name:'Evil',	replaceWith:':evil:', className:"evil" },
                            {name:'Twisted',	replaceWith:':twisted:', className:"twisted" },
                            {name:'Razz',	replaceWith:':razz:', className:"razz" },
                            {name:'Blush',	replaceWith:':blush:', className:"blush" },
                            {name:'Neutral',	replaceWith:':neutral:', className:"neutral" },
                            {name:'Mad',	replaceWith:':mad:', className:"mad" },
                            {name:'Eek',	replaceWith:':eek:', className:"eek" },
                            {name:'Roll Eyes',	replaceWith:':roll_eyes:', className:"roll_eyes" },
                            {name:'Frown',	replaceWith:':frown:', className:"frown" },
                ] },
	]
}