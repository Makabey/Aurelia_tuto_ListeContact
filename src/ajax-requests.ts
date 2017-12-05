import * as jQuery from 'jquery';

export class AjaxRequests
{
    _config = {'method':'post', 'url':'./src/ajax-requests.php'};

    request(module:string, operation:string, parametres:any=[]):any
    {
        let dataOut_o = {
                'file':module,
                'function':operation,
                'params':parametres
            };
        let dataOut = JSON.stringify(dataOut_o);

        //function onDone(data:any){}
        //function onFail(err:any){}
        //function onComplete(){}

        /**
         * ToDo : trouver moyen de déterminer si .responseJSON.statut == 'ok'
         * ou non et retourner FALSE si != 'ok'.
         */
        return jQuery.ajax({
            url: this._config['url'],
            type: this._config['method'],
            data: dataOut,
            dataType: "json",
            //done: function (data) { return onDone(data); },
            //fail: function (err) { onFail(err); },
            //complete: function () { onComplete(); },
            //contentType: false,
            processData: true,
            async: false,
        }).responseJSON.response;
    }
}

// == EOF ==
