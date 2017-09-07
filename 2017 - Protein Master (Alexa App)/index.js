'use strict';
var Alexa = require('alexa-sdk');
const request = require("request");

var APP_ID = "amzn1.ask.skill.7985a8dd-7498-4713-811c-06f58d46c8fc";

var SKILL_NAME = "Protein";
var ERROR = "Sorry, the food was not found. Try something else.";
var HELP_MESSAGE = "What can I find you?";
var HELP_REPROMPT = "What can I help you find?";
var STOP_MESSAGE = "Goodbye!";

const find = food => {
    return new Promise(function (accept, reject){
        const options = { method: 'POST',
          url: 'https://trackapi.nutritionix.com/v2/natural/nutrients',
          headers: 
           { 'x-app-key': '257c2101be36930421e7470e7d48fd08',
             'x-app-id': 'c6f61de5' },
          form: { query: food } };

        request(options, (error, response, body) => {
          if (error) return reject();
          let res = JSON.parse(body);
          if (!res.foods || res.foods.length === 0) return reject();
          res = res.foods[0]; 

          return accept(res.serving_qty + " " + res.serving_unit + " of " + res.food_name + " has " + res.nf_protein + " grams of protein.");
        });
    });
}

//=========================================================================================================================================
//Editing anything below this line might break your skill.  
//=========================================================================================================================================
exports.handler = function(event, context, callback) {
    var alexa = Alexa.handler(event, context);
    alexa.APP_ID = APP_ID;
    alexa.registerHandlers(handlers);
    alexa.execute();
};

var handlers = {
    'LaunchRequest': function () {
        this.emit(':ask', HELP_MESSAGE);
    },
    'GetProteinIntent': function () {
        if(!this.event.request.intent) {
            return this.emit(':ask', HELP_MESSAGE);
        }
        const food = this.event.request.intent.slots.Food;
        if(!food.value) {
            return this.emit(':ask', HELP_MESSAGE);
        }
        find(food.value.toLowerCase())
        .then(output => {
            this.emit(':tellWithCard', output, SKILL_NAME, output); 
        })
        .catch(() => {
            this.emit(':tell', ERROR);
        })
    },
    'AMAZON.HelpIntent': function () {
        var speechOutput = HELP_MESSAGE;
        var reprompt = HELP_REPROMPT;
        this.emit(':ask', speechOutput, reprompt);
    },
    'AMAZON.CancelIntent': function () {
        this.emit(':tell', STOP_MESSAGE);
    },
    'AMAZON.StopIntent': function () {
        this.emit(':tell', STOP_MESSAGE);
    },
    'Unhandled': function () {
        this.emit(':ask', HELP_MESSAGE);
    }
};


