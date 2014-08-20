// main app object
var BtnApp = {
    data: {
        debug: true
    },
    triggerState: function (state, input) {
        var statePrefixed = 'btn_admin.' + state;
        switch (typeof input) {
            case 'function':
                PubSub.subscribe(statePrefixed, input);
                break;
            case 'object':
            case 'undefined':
                var parms = {context: this.tools.getContext(input)};
                this.tools.log(statePrefixed, parms);
                PubSub.publish(statePrefixed, parms);
                break;
        }
    },
    init: function(input) {
        this.triggerState('init', input);
    },
    ready: function(input) {
        this.triggerState('ready', input);
    },
    refresh: function(input) {
        this.triggerState('refresh', input);
    },
};

// handy tools
BtnApp.tools = {
    log: function() {
        BtnApp.data.debug ? console.log.apply(console, arguments) : null;
    },
    warn: function() {
        BtnApp.data.debug ? console.warn.apply(console, arguments) : null;
    },
    error: function() {
        BtnApp.data.debug ? console.warn.error(console, arguments) : null;
    },
    isNode: function(o){
        return (typeof Node === "object" ? o instanceof Node : o && typeof o === "object" && typeof o.nodeType === "number" && typeof o.nodeName==="string");
    },
    isElement: function(o){
        return (typeof HTMLElement === "object" ? o instanceof HTMLElement : o && typeof o === "object" && o !== null && o.nodeType === 1 && typeof o.nodeName==="string");
    },
    getContext: function(input) {
        if ('undefined' === typeof input) {
            return document;
        } else if (this.isElement(input)) {
            return input; // regulat input
        } else if (input.context) {
            return input.context; // object with context key
        } else if (1 === input.length && this.isElement(input[0])) {
            return input[0]; // probably one element jquery object
        } else {
            return document;
        }
    },
    getOnce: function(selector, context) {
        return jQuery(context || document).find('[data-' + selector + ']')
            .filter(':not([data-' + selector + '-binded])')
            .attr('data-' + selector + '-binded', true)
        ;
    }
}
