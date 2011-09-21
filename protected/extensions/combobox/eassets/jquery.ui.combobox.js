
(function($) {
	$.widget("ui.combobox", {
		_create: function() {
			var options = this.options;

			var self = this,
			input = this.element,
			select = input.prev("select").hide();

			input.autocomplete({
				delay: 0,
				minLength: 0,
				source: function(request, response) {
					var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
					response(select.children("option").map(function() {
						var text = $(this).text();
						if (this.value && (!request.term || matcher.test(text)))
							return {
								label: text.replace(
									new RegExp(
										"(?![^&;]+;)(?!<[^<>]*)(" +
										$.ui.autocomplete.escapeRegex(request.term) +
										")(?![^<>]*>)(?![^&;]+;)", "gi"
										), "<strong>$1</strong>" ),
								value: text,
								option: this
							};
					}));
				},
				select: function(event, ui) {
					var item = ui.item
					item.option.selected = true;
					self._trigger("selected", event, {
						item: item.option
					});
					eval(options.onSelect);
				},
				change: function(event, ui) {
					if (!ui.item) {
						if (!options.allowText) {
							var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex($(this).val() ) + "$", "i"),
							valid = false;
							select.children("option").each(function() {
								if ($(this).text().match(matcher)) {
									this.selected = valid = true;
									return false;
								}
							});
							if ( !valid ) {
								// remove invalid value, as it didn't match anything
								$( this ).val( "" );
								select.val( "" );
								input.data( "autocomplete" ).term = "";
								return false;
							}
						}
						eval(options.onChange);
					}
				}
			})
			.addClass("ui-widget ui-widget-content ui-corner-left");

			input.data("autocomplete")._renderItem = function(ul, item) {
				return $("<li></li>")
				.data("item.autocomplete", item)
				.append("<a>" + item.label + "</a>")
				.appendTo(ul);
			};

			this.button = $("<button type='button'>&nbsp;</button>")
			.attr("tabIndex", -1 )
			.attr("title", "Show All Items")
			.attr("id", 'btn')
			.insertAfter(input)
			.button({
				icons: {
					primary: "ui-icon-triangle-1-s"
				},
				text: false
			})
			.removeClass("ui-corner-all")
			.addClass("ui-corner-right ui-button-icon")
			.click(function() {
				// close if already visible
				if (input.autocomplete("widget").is(":visible")) {
					input.autocomplete("close");
					return;
				}
				// work around a bug (likely same cause as #5265)
				$(this).blur();
				// pass empty string as value to search for, displaying all results
				input.autocomplete("search", "");
				input.focus();
			});
		},
		destroy: function() {
			this.input.remove();
			this.button.remove();
			this.element.show();
			$.Widget.prototype.destroy.call(this);
		}
	});
})(jQuery);
