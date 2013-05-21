var RTOOLBAR = {
	styles:
	{
		title: RLANG.styles,
		func: 'show',
		separator: true
	},
	bold:
	{
		title: RLANG.bold,
		exec: 'bold'
	}, 
	italic: 
	{
		title: RLANG.italic,
		exec: 'italic',
		separator: true		
	},
	insertunorderedlist:
	{
		title: '&bull; ' + RLANG.unorderedlist,
		exec: 'insertunorderedlist'
	},
	insertorderedlist: 
	{
		title: '1. ' + RLANG.orderedlist,
		exec: 'insertorderedlist'
	},
	outdent: 
	{	
		title: '< ' + RLANG.outdent,
		exec: 'outdent'
	},
	indent:
	{
		title: '> ' + RLANG.indent,
		exec: 'indent',
		separator: true
	},
	link:
	{ 
		title: RLANG.link, 
		func: 'show', 				
		dropdown: 
		{
			link: 	{name: 'link', title: RLANG.link_insert, func: 'showLink'},
			unlink: {exec: 'unlink', name: 'unlink', title: RLANG.unlink}
		}															
	}
};