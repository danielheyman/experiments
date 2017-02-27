ContactManager.module("Entities", function(Entities, ContactManager, Backbone, Marionette, $, _){
  Entities.FilteredCollection = function(options){
    var original = options.collection;
    var filtered = new original.constructor();
    filtered.add(original.models);
    filtered.filterFunction = options.filterFunction;

    var applyFilter = function(filterCriterion, filterStrategy, collection){
      var collection = collection || original;
      var criterion;
      if(filterStrategy == "filter"){
        criterion = filterCriterion.trim();
      }
      else{
        criterion = filterCriterion;
      }

      var items = [];
      if(criterion){
        if(filterStrategy == "filter"){
          if( ! filtered.filterFunction){
            throw("Attempted to use 'filter' function, but none was defined");
          }
          var filterFunction = filtered.filterFunction(criterion);
          items = collection.filter(filterFunction);
        }
        else{
          items = collection.where(criterion);
        }
      }
      else{
        items = collection.models;
      }

      // store current criterion
      filtered._currentCriterion = criterion;

      return items;
    };

    filtered.filter = function(filterCriterion){
      filtered._currentFilter = "filter";
      var items = applyFilter(filterCriterion, "filter");

      // reset the filtered collection with the new items
      filtered.reset(items);
      return filtered;
    };

    filtered.where = function(filterCriterion){
      filtered._currentFilter = "where";
      var items = applyFilter(filterCriterion, "where");

      // reset the filtered collection with the new items
      filtered.reset(items);
      return filtered;
    };

    // when the original collection is reset,
    // the filtered collection will re-filter itself
    // and end up with the new filtered result set
    original.on("reset", function(){
      var items = applyFilter(filtered._currentCriterion, filtered._currentFilter);

      // reset the filtered collection with the new items
      filtered.reset(items);
    });

    // if the original collection gets models added to it:
    // 1. create a new collection
    // 2. filter it
    // 3. add the filtered models (i.e. the models that were added *and*
    //     match the filtering criterion) to the `filtered` collection
    original.on("add", function(models){
      var coll = new original.constructor();
      coll.add(models);
      var items = applyFilter(filtered._currentCriterion, filtered._currentFilter, coll);
      filtered.add(items);
    });

    return filtered;
  };

  Entities.BaseModel = Backbone.Model.extend({
    refresh: function(serverData, keys){
      var previousAttributes = this.previousAttributes();
      var changed = this.changedAttributes();

      this.set(serverData);
      if(changed){
        this.set(changed, {silent: true});
        keys = _.difference(keys, _.keys(changed))
      }
      var clientSide = _.pick(previousAttributes, keys);
      var serverSide = _.pick(serverData, keys);
      this.set({
        changedOnServer: ! _.isEqual(clientSide, serverSide)
      }, {silent: true});
    },

    sync: function(method, model, options){
      return Backbone.Model.prototype.sync.call(this, method, model, options);
    }
  });

  var originalSync = Backbone.sync;
  Backbone.sync = function (method, model, options) {
    var deferred = $.Deferred();
    options || (options = {});
    deferred.then(options.success, options.error);

    var response = originalSync(method, model, _.omit(options, 'success', 'error'));

    response.done(deferred.resolve);
    response.fail(function() {
        if(response.status == 401){
          alert("This action isn't authorized!");
        }
        else if(response.status === 403){
          alert(response.responseJSON.message);
        }
        else{
          deferred.rejectWith(response, arguments);
        }
    });

    return deferred.promise();
  };

  _.extend(Backbone.Validation.messages, {
    required: 'is required',
    minLength: 'is too short (min {1} characters)'
  });
});
