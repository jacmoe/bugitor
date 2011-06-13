/**
 * jQuery Hash Handling plugin file.
 * This plugin requires JQuery (obviously), and the BBQ plugin by Ben Alman
 * (http://benalman.com/projects/jquery-bbq-plugin/).
 * It sets up a handling system for various components that might wish to store state info (that is also used
 * as normal querystring GET parameters, presumably for non Javascript enabled systems) in the hash fragment, 
 * allowing them to register a handler for a group (which could just include one) of GET parameters.
 * These parameters will then be monitored, and the callback function supplied by the component will
 * be called in case of change.
 * The callback function also contains an error function which allows the hash parameter(s) to be reset
 * to their original state in case of an error during the callback (Ajax call which doesn't work for example).
 *
 * @author Rupert, Red Rabbit Studio
 * @link http://www.rabbitstyle.com/
 * @copyright Copyright &copy; 2011 Red Rabbit Studio
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version $Id: jquery.rr.hashhandling.js 2 2011-02-07
 */

(function($) {
	/**
	 * The default storage space for the hash handling groups and individuals
	 */
	$.hashHandler = {
		handlerGroups: {},
		handlerDefaults: {
			parameter: '',
			previousValue: null
		},
		groupDefaults: {
			resetError: false,
			callback: {}
		}
	};
	
	/**
	 * registerHashHandler function, extends JQuery.
	 * @param options map settings for the param handler. Available options are as follows:
	 * - parameter: the GET parameter that is to be monitored
	 * - previousValue: where the last value of the param will be stored - defaults to null initially
	 * @param name string giving the name/index of the handler
	 * @param group string giving the name/index of the group to which the handler belongs
	 * @param groupOptions map settings for the group handler. Available options are as follows:
	 * - callback: the callback function to be called when a hash change affecting the group is detected.
	 *   the function should be in the following format: function (params, error)
	 *   where params is an array containing an array for each param of the group, containing previous
	 *   and next, the previous and current values for that param, and options is a function to be
	 *   called in case of error, to revert to the previous hash, that does not take any parameters.
	 * - resetError: boolean flag used to store whether a callback function should be processed for the
	 *   hash change or not (not if an error call has reverted the hash).
	 */
	$.extend({
		registerHashHandler: function(options, name, group, groupOptions) {
			var handlerSettings = $.extend({}, $.hashHandler.handlerDefaults, options || {});
			if ($.hashHandler.handlerGroups[group] === undefined) {
				$.hashHandler.handlerGroups[group] = {};
				$.hashHandler.handlerGroups[group].settings = $.extend({}, $.hashHandler.groupDefaults, groupOptions);
				$.hashHandler.handlerGroups[group].handlers = {};
			}
			$.hashHandler.handlerGroups[group].handlers[name] = $.extend({}, $.hashHandler.handlerDefaults, options);
		},
		/**
		 * getParams function, extends JQuery
		 * @param querystring string containing the (non URIdecoded) query to be parsed into an
		 *        object array (of only 1 level of depth, so that level[level2]=4 does not generate
		 *        an object level with child object level2 with value 4, but rather simply an
		 *        object level[level2] with value 4
		 * @returns queryString, the parsed object 
		 */
		getParams: function (querystring) {
		    var queryString = {};
		    decodeURI(querystring).replace(
		        new RegExp("([^?=&]+)(=([^&]*))?", "g"),
		        function($0, $1, $2, $3) { queryString[$1] = $3; }
		    );
		    return queryString;
		}
	});
	
	/* the hash change event handler, which is bound immediately */
	$(window).bind('hashchange.hashHandler', function(event) {
		// get Url params
		var pageUrl = $.getParams($.param.querystring());
		// get new hash
		var hashUrl = $.getParams($.param.fragment());
		var callbacks = {};
		/** 
		 * Loop through the handler groups, looping through all the param handlers in each group to see
		 * whether a callback should be fired for that group.
		 * If resetError is found to be set for that group, we don't fire any callbacks but just reset
		 * the error flag.
		 * For each group we collect an array of the previous and current values of the hash param
		 * (storing undefined values as null), and if any params have changed we modify the changed
		 * flag for the group.
		 */ 
		$.each($.hashHandler.handlerGroups, function(index, handlerGroup) {
			var change = false;
			var params = {};
			// if just a reset error then don't do anything apart from resetting the flag
			if (handlerGroup.settings.resetError) {
				handlerGroup.settings.resetError = false;
			} else {
				// loop through each group handler
				$.each(handlerGroup.handlers, function(name, handler) {
					// store the previous value before overwriting with the current value
					var previousVal = handler.previousValue;
					// initialise the param value array
					params[handler.parameter] = {
						previous: previousVal,
						next: previousVal
					};
					// there has been a change in the param concerned
					if ((hashUrl[handler.parameter] === undefined && previousVal !== null) || (hashUrl[handler.parameter] !== undefined && hashUrl[handler.parameter] !== previousVal)) {
						// use null in storage to indicate the absence of the param
						if (hashUrl[handler.parameter] === undefined) {
							handler.previousValue = null;
						} else {
							// otherwise store the value
							handler.previousValue = hashUrl[handler.parameter];
						}
						/**
						 * extract the determined value for the param, taking into account both the hash
						 * fragment, and the query string, and using null to indicate the absence of
						 * the param
						 */
						var generalParam = handler.previousValue;
						if (generalParam === null && pageUrl[handler.parameter] !== undefined) {
							generalParam = pageUrl[handler.parameter];
						}
						// store the value of the current param
						params[handler.parameter].next = generalParam;
						// set the change flag for the group
						change = true;
					}
				});
				// if there has been a change for the group
				if (change) {
					// add the group callback to the array of callbacks to be made
					callbacks[index] = {
						params: params,
						callback: handlerGroup.settings.callback,
						error: function() {
							// in case of error, restore previous hash value
							// get the current hash
							var curr = $.getParams($.param.fragment());
							// loop through the params used by this group
							for(gparam in params) {
								// if the param was absent previously, remove it from the hash
								if (curr[gparam] !== undefined && params[gparam].previous === null) {
									delete curr[gparam];
								} else {
									// otherwise set its value
									curr[gparam] = params[gparam].previous;
								}
							}
							// set error flag
							handlerGroup.resetError = true;
							// change the hash back to its previous state
							$.bbq.pushState($.param(curr), 2);
						} 
					};
				}
			}
		});
		// call each handler for the changed parameters
		for(x in callbacks) {
			callbacks[x].callback(callbacks[x].params, callbacks[x].error);
		}
	});
	
}(jQuery));