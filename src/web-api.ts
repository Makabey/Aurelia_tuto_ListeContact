import {AjaxRequests} from './ajax-requests';

export class WebAPI {
  isRequesting = false;
  ar : AjaxRequests = new AjaxRequests();
  //libDB : string = 'db_test_JSON';
  libDB : string = 'db_test_CSV';

  getContactList(){
    this.isRequesting = true;
    return new Promise(resolve => {
      let data = this.ar.request(this.libDB, 'getContacts');
      let contacts = Object.values(data);

      resolve(contacts);
      this.isRequesting = false;
    });
  }

  getContactDetails(id){
    this.isRequesting = true;
    return new Promise(resolve => {
      let found = this.ar.request(this.libDB, 'getContactDetails', id);

      resolve(found);
      this.isRequesting = false;
    });
  }

  saveContact(contact){
    this.isRequesting = true;
    return new Promise(resolve => {
      let instance = JSON.parse(JSON.stringify(contact));

      instance = this.ar.request(this.libDB, 'saveContact', instance);
      resolve(instance);
      this.isRequesting = false;
    });
  }
}
