/*
* Enhancements for the Manuscript Verse Miscellany Project
* @author Joey Takeda
* @author DHIL
* @started 2020
*
* */

import dialogPolyfill from '../yarn/dhilux/js/modals.bundle.js';
import Modals from '../yarn/dhilux/js/modals.js';


const docId = document.querySelector('html').id;
const entities = ['person','theme','region','period','coterie', 'print_source', 'archive'].filter(isIndex);

(function(){
    if (!(/(edit|new)\/?$/gi.test(location) || docId == 'index')){
        makeModals();
    }
})();


function makeModals(){
    let selector = entities.map(ent => `a[href*="${ent}"]`).join(', ');
    let modals = new Modals(selector);
    modals.el = document.querySelector('main');
    modals.filterParentsSelector = '.gallery, html[id$="_index"], header, p';
    modals.linkFilter = (link) => {
        console.log(link);
        let table = link.closest('table');
        if (table){
            let thead = table.querySelector('thead');
            return (thead === null);
        }
    }
    modals.debug = true;
    modals.init();
}

function isIndex(entity) {
    let rex = new RegExp(`\\/${entity}\\/?$`, 'gi');
    return !(rex.test(window.location.href));
}












