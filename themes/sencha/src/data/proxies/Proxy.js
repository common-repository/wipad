/**
 * @class Ext.data.Proxy
 * @extends Ext.util.Observable
 * <p>Base Proxy class. This provides an interface that all Proxy subclasses must honor and a small number of shared functions. This
 * Proxy should never be used directly, instead use one of its subclasses.</p>
 * <p>Proxies are used internally by {@link Ext.data.Store stores}, and operate on the principle that all operations performed are either
 * Create, Read, Update or Delete. These four operations are mapped to the methods {@link #create}, {@link #read}, {@link #update} and {@link #destroy}
 * respectively. Each Proxy subclass must implement these functions</p>
 * <p>The CRUD methods each expect an {@link Ext.data.Operation operation} object as the sole argument. The Operation encapsulates information about
 * the action the Store wishes to perform, the {@link Ext.data.Model model} instances that are to be modified, etc. See the {@link Ext.data.Operation Operation}
 * documentation for more details. Each CRUD method also accepts a callback function to be called asynchronously on completion.</p>
 * <p>Proxies also support batching of Operations via a {@link Ext.data.Batch batch} object, invoked by the {@link #batch} method.</p>
 * @constructor
 * Creates the Proxy
 * @param {Object} config Optional config object
 */
Ext.data.Proxy = Ext.extend(Ext.util.Observable, {
    /**
     * @cfg {String} batchOrder
     * Comma-separated ordering 'create', 'update' and 'destroy' actions when batching. Override this
     * to set a different order for the batched CRUD actions to be executed in. Defaults to 'create,update,destroy'
     */
    batchOrder: 'create,update,destroy',
    
    /**
     * @ignore
     */
    constructor: function(config) {
        Ext.data.Proxy.superclass.constructor.call(this, config);
        
        Ext.apply(this, config || {});
    },
    
    /**
     * Sets the model associated with this proxy. This will only usually be called by a Store
     * @param {String|Ext.dataModel} model The new model. Can be either the model name string,
     * or a reference to the model's constructor
     */
    setModel: function(model) {
        if (typeof model == 'string') {
            model = Ext.ModelMgr.types[model];
        }
        
        this.model = model;
    },
    
    /**
     * Performs the given create operation.
     * @param {Ext.data.Operation} operation The Operation to perform
     * @param {Function} callback Callback function to be called when the Operation has completed (whether successful or not)
     * @param {Object} scope Scope to execute the callback function in
     */
    create: Ext.emptyFn,
    
    /**
     * Performs the given read operation.
     * @param {Ext.data.Operation} operation The Operation to perform
     * @param {Function} callback Callback function to be called when the Operation has completed (whether successful or not)
     * @param {Object} scope Scope to execute the callback function in
     */
    read: Ext.emptyFn,
    
    /**
     * Performs the given update operation.
     * @param {Ext.data.Operation} operation The Operation to perform
     * @param {Function} callback Callback function to be called when the Operation has completed (whether successful or not)
     * @param {Object} scope Scope to execute the callback function in
     */
    update: Ext.emptyFn,
    
    /**
     * Performs the given destroy operation.
     * @param {Ext.data.Operation} operation The Operation to perform
     * @param {Function} callback Callback function to be called when the Operation has completed (whether successful or not)
     * @param {Object} scope Scope to execute the callback function in
     */
    destroy: Ext.emptyFn,
    
    /**
     * Performs a batch of {@link Ext.data.Operation Operations}, in the order specified by {@link #batchOrder}. Used internally by
     * {@link Ext.data.Store}'s {@link Ext.data.Store#sync sync} method. Example usage:
     * <pre><code>
     * myProxy.batch({
     *     create : [myModel1, myModel2],
     *     update : [myModel3],
     *     destroy: [myModel4, myModel5]
     * });
     * </code></pre>
     * Where the myModel* above are {@link Ext.data.Model Model} instances - in this case 1 and 2 are new instances and have not been 
     * saved before, 3 has been saved previously but needs to be updated, and 4 and 5 have already been saved but should now be destroyed.
     * @param {Object} operations Object containing the Model instances to act upon, keyed by action name
     * @param {Object} listeners Optional listeners object passed straight through to the Batch - see {@link Ext.data.Batch}
     * @return {Ext.data.Batch} The newly created Ext.data.Batch object
     */
    batch: function(operations, listeners) {
        var batch = new Ext.data.Batch({
            proxy: this,
            listeners: listeners || {}
        });
        
        Ext.each(this.batchOrder.split(','), function(action) {
            batch.add(new Ext.data.Operation({
                action : action, 
                records: operations[action]
            }));
        }, this);
        
        batch.start();
        
        return batch;
    }
});

//backwards compatibility
Ext.data.DataProxy = Ext.data.Proxy;

Ext.data.ProxyMgr.registerType('proxy', Ext.data.Proxy);