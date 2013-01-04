/**
 * jQuery Yii GridView plugin file extension.
 *
 * This extension overwrites the $.fn.yiiGridView namespace with an alternative
 * constructor function and adds some helper functions, in order to allow the GridView
 * to create a hash history.
 *
 * @author Rupert, Red Rabbit Studio
 * @link http://www.rabbitstyle.com/
 * @copyright Copyright &copy; 2010-2011 Red Rabbit Studio
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version $Id: jquery.rr.yiigridview.js 6 2011-06-10
 */

(function($) {
	/* backup all the $.fn.yiiListView objects so that we can reinsert them after overriding
	   the constructor function */
	var tmpListView = {};
	for (attr in $.fn.yiiListView) {
		tmpListView[attr] = $.fn.yiiListView[attr];
	}
	
	/**
	 * yiiListView set function.
	 * @param options map settings for the list view. Available options are as follows:
	 * - ajaxUpdate: array, IDs of the containers whose content may be updated by ajax response
	 * - ajaxVar: string, the name of the GET variable indicating the ID of the element triggering the AJAX request
	 * - pagerClass: string, the CSS class for the pager container
	 * - sorterClass: string, the CSS class for the sorter container
	 * - updateSelector: string, the selector for choosing which elements can trigger ajax requests
	 * - beforeAjaxUpdate: function, the function to be called before ajax request is sent
	 * - afterAjaxUpdate: function, the function to be called after ajax response is received
	 */
	$.fn.yiiListView = function(options) {
		return this.each(function(){
			var settings = $.extend({}, $.fn.yiiListView.defaults, options || {});
			var $this = $(this);
			var id = $this.attr('id');
			if(settings.updateSelector == undefined) {
				settings.updateSelector = '#'+id+' .'+settings.pagerClass.replace(/\s+/g,'.')+' a, #'+id+' .'+settings.sorterClass.replace(/\s+/g,'.')+' a';
			}
			$.fn.yiiListView.settings[id] = settings;
			
			/* ********* */
			// initialise the GET parameter array
			var getParams = [];
			/* --------- */

			if(settings.ajaxUpdate.length > 0) {
				/* ********* */
				// if hashHistory is enabled bind the appropriate click functions, otherwise continue with default
				if (settings.updaters === undefined) {
					/* --------- */
					$(settings.updateSelector).die('click').live('click',function(){
						$.fn.yiiListView.update(id, {url: $(this).attr('href')});
						return false;
					});
				/* ********* */
				}
				else {
					if (settings.updaters.page !=undefined && settings.updaters.page.selector === undefined) {
						settings.updaters.page.selector = '#' + id + ' .' + settings.pagerClass.replace(/\s+/g,'.') + ' a';
					}
					if (settings.updaters.sort !=undefined && settings.updaters.sort.selector === undefined) {
						settings.updaters.sort.selector = '#' + id + ' .' + settings.sorterClass.replace(/\s+/g,'.') + ' a';
					}
					/* loop through all the updaters, adding the GET params to the parameter array
					   and binding the handlers to the links */
					for (updater in settings.updaters) {
						// the updater gets its values from an input
						if (settings.updaters[updater].getParams === undefined) {
							$(settings.updaters[updater].selector).each(function() {
								getParams.push($(this).attr('name'));
							});
							$('body').undelegate(settings.updaters[updater].selector, 'change').delegate(settings.updaters[updater].selector, 'change', function(){
								var context = $(this);
								var params = {};
								/* artificially create the param object from the input name with
								 * null as the initial value (so as not to confuse deparam with 
								 * potential = and & characters in the value)
								 * (allows variable depth objects like Model[attribute] = value */
								var param = $.deparam(context.attr('name') + '=null', true);
								// add in the value if it is not blank
								if (context.val() !== '') {
									$.fn.yiiListView.setValue(param, context.val());
								}
								// loop through each attribute of the param object in order to insert
								// the param object into the params object
								for(attr in param) {
									params[attr] = param[attr];
								}
								// if pagination is enabled, then by default we reset the page to 1 on filtering/sorting
								if ($.fn.yiiListView.settings[id].updaters.page !== undefined) {
									// this assumes that the page updater has a single GET param as the first index of the GET params array
									var pageParam = $.deparam($.fn.yiiListView.settings[id].updaters.page.getParams[0] + '=1');
									for (attr in pageParam) {
										params[attr] = pageParam[attr];
									}
								}
								// update the current hash with the new input state
								$.fn.yiiListView.updateHash(params);
								return false;
							});
						}
						// otherwise the updater gets its values from a link
						else {
							getParams = getParams.concat(settings.updaters[updater].getParams);
							// use a function creator to avoid closure problems with the updater local variable
							var clFunc = function(thisUpdater) {
								return function(){
									var x = $.fn.yiiListView.settings[id].updaters[thisUpdater].getParams.length;
									// get the query parameter objects from the link location
									var linkParams = $.deparam.querystring($(this).attr('href'));
									var params = {};
									/* loop through all the GET params for this updater, creating an object
									 * with null value, and overriding that value if there is a value in the
									 * query parameters of the link */
									for(i = 0; i < x; i++) {
										var param = $.deparam($.fn.yiiListView.settings[id].updaters[thisUpdater].getParams[i] + '=null', true);
										$.fn.yiiListView.findValue(param, linkParams);
										for (attr in param) {
											params[attr] = param[attr];
										}
									}
									// update the current hash with the new pageVar state
									$.fn.yiiListView.updateHash(params);
									return false;
								};
							};
							$(settings.updaters[updater].selector).die('click').live('click', clFunc(updater));
						}
					}
				}
				/* --------- */
			}
			/* ********* */
			// if hash history is enabled and there are GET parameters to be monitored...
			if (settings.updaters !== undefined && getParams !== undefined && getParams.length > 0) {
				$.fn.yiiListView.arrayFilter(getParams);
				var x = 0;
				var group = id + 'ListView';
				// register the hash handler group for this listview, using the first GET parameter
				$.registerHashHandler({
					parameter: getParams[x]
				}, getParams[x], group, {
					// the callback for the hash handler that allows us to update the listview
					// according to the current hash state
					callback: function(params, error) {
						// merge the current location and hash fragment parameters
						var pageParams = $.extend($.fn.yiiListView.parseParams($.param.querystring()), $.fn.yiiListView.parseParams($.param.fragment()));
						// loop through the changed parameters
						for(param in params) {
							if (params[param].next === null) {
								if (pageParams[param] !== undefined) {
									delete pageParams[param];
								}
							} else {
								// otherwise set it to the new value
								pageParams[param] = params[param].next;
							}
						}
						/*
						 * make the Ajax update, using the new Url, and overriding
						 * the error function to include the hash error callback
						 * (so that the hash can be changed back to its previous
						 * state in case of error)
						 */
						var data = '';
						// serialize the parameters
						for(param in pageParams) {
							data += '&' + param + '=' + pageParams[param];
						}
						// make the ajax update request
						$.fn.yiiListView.update(id, {
							url: window.location.href.split('?')[0],
							data: data,
							error: function(XMLHttpRequest, textStatus, errorThrown) {
								$('#'+id).removeClass(settings.loadingClass);
								alert(XMLHttpRequest.responseText);
								error();
							},
							cache: false
						});
					}
				});
				// loop through the remaining GET parameters, adding each one to the hash handler
				var y = getParams.length;
				for(x = 1; x < y; x++) {
					$.registerHashHandler({
						parameter: getParams[x]
					}, getParams[x], group, {});
				}
			}
			// trigger the hash change event for the first time in case an ajax link has been accessed
			$(window).trigger('hashchange');
			/* --------- */
		});
	};
	
	/* ********* */
	/**
	 * Updates the hash state with the parameter objects in params
	 * @param params the object containing the parameter objects to be updated
	 */
	$.fn.yiiListView.updateHash = function(params) {
		// get the parameters from the location
		var pageParams = $.deparam.querystring();
		// get the current hash state
		var state = $.bbq.getState();
		// merge the params with the current hash state
		state = $.extend(true, state, params);
		// check the updated params for null values or redundant values that can be left out of the hash state
		$.fn.yiiListView.checkCompare(params, pageParams, state);
		// update the hash state
		$.bbq.pushState(state, 2);
	};

	/**
	 * Parses a querystring to return an array of parameters
	 * @param querystring the querystring to be parsed
	 * @return object an associative array of parameter names and values
	 */
	$.fn.yiiListView.parseParams = function (querystring) {
	    var queryString = {};
	    decodeURI(querystring).replace(
	        new RegExp("([^?=&]+)(=([^&]*))?", "g"),
	        function($0, $1, $2, $3) { queryString[$1] = $3; }
	    );
	    return queryString;
	};
	
	/**
	 * Recursively traverses all atrributes and sub-attributes of obj 
	 * in order to set its lowest-level child attribute
	 * @param obj the object whose attributes are to be traversed
	 * @param value the value to be assigned to the attribute
	 */
	$.fn.yiiListView.setValue = function(obj, value) {
		for (attr in obj) {
			if (obj[attr] === null) {
				obj[attr] = value;
				return true;
			}
			else {
				if (typeof(obj[attr]) === 'object') {
					return $.fn.yiiListView.setValue(obj[attr], value);
				}
				else {
					return false;
				}
			}
		}
		return false;
	};
	
	/**
	 * Recursively traverses all atrributes and sub-attributes of obj 
	 * in order to retrieve the equivalent value, from subject, 
	 * of its lowest-level child
	 * @param obj the object whose attributes are to be traversed
	 * @param subject the object whose attribute is to be found
	 */
	$.fn.yiiListView.findValue = function(obj, subject) {
		for (attr in obj) {
			if (obj[attr] === null) {
				if (subject[attr] === undefined) {
					return false;
				}
				else {
					obj[attr] = subject[attr];
					return true;
				}
			}
			else {
				if (typeof(obj[attr]) === 'object') {
					if (subject[attr] !== undefined || typeof(subject[attr]) === 'object') {
						return $.fn.yiiListView.findValue(obj[attr], subject[attr]);
					}
					else {
						return false;
					}
				}
				else {
					return false;
				}
			}
		}
		return false;
	};
	
	/**
	 * Recursively compares a new params object, the parameters included in
	 * the current location, and the current hash state, to determine whether
	 * any parameters should be deleted from the hash state (such as those
	 * whose values are null, or whose values are the same as those given by
	 * the location Url)
	 * @param contained the params object containing the parameter objects
	 *                  that have been updated
	 * @param container the location parameters object containing the
	 *                  parameter objects derived from the location Url
	 * @param state the current state object containing the parameter objects
	 *              that may be updated.
	 */
	$.fn.yiiListView.checkCompare = function (contained, container, state) {
		for (attr in contained) {
			if (typeof(contained[attr]) === 'object') {
				var subContainer = null;
				if (container !== null && container[attr] !== undefined) {
					subContainer = container[attr];
				}
				if ($.fn.yiiListView.checkCompare(contained[attr], subContainer, state[attr])) {
					delete state[attr];
				}
			}
			else {
				if ((contained[attr] === null && (container === null || container[attr] === undefined)) || (container !== null && container[attr] !== undefined && contained[attr] === container[attr])) {
					delete state[attr];
				}
			}
		}
		var count = 0;
		for (attr in state) {
			count++;
		}
		if (count === 0) {
			return true;
		}
		return false;
	};
	
	/**
	 * Traverses an array object removing any duplicate items
	 * @param obj the array to be traversed
	 */
	$.fn.yiiListView.arrayFilter = function (obj) {
		var x = obj.length;
		for(i = 0; i < x; i++) {
			for(j = i + 1; j< x; j++) {
				if (obj[i] === obj[j]) {
					obj.splice(j, 1);
					x--;
				}
			}
		}
	};
	
	/* reinsert the $.fn.yiiGridView objects that we backup up before
	   overwriting the constructor function */
	for(attr in tmpListView) {
		// only reinsert the object if we haven't created our own
		if ($.fn.yiiListView[attr] === undefined) {
			$.fn.yiiListView[attr] = tmpListView[attr];
		}
	}
}(jQuery));