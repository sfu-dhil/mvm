/*
* Enhancements for the Manuscript Verse Miscellany Project
* @author Joey Takeda
* @author DHIL
* @started 2020
*
* */

import Modals from 'dhilux/js/modals.bundle.js';
// core version + navigation, pagination modules:
import Swiper, { Navigation } from 'swiper';

const docId = document.querySelector('html').id;
const entities = ['person','theme','region','period','coterie', 'print_source', 'archive'].filter(isIndex);


init();

function init() {
    console.log('init');
    makeHomepageGallery();
    if (!(/(edit|new)\/?$/gi.test(location) || docId == 'index')) {
        makeModals();
    }

}

function makeHomepageGallery() {
    let q = '.archive-gallery';
    let gallery = document.querySelector(q);
    console.log(gallery);
    if (!gallery) {
        return;
    }
    Swiper.use([Navigation]);
    let cfg = {
       // centeredSlides: true,
        spaceBetween: 16,
        slidesPerView: 3,
       // loop: true,
        navigation: {}
    }
    let wrapper = document.createElement('div');
    gallery.insertAdjacentElement('beforebegin', wrapper);
    wrapper.appendChild(gallery);
    wrapper.classList.add('swiper');
    gallery.classList.add('swiper-wrapper');
    [...gallery.children].forEach(child => {
        child.classList.add('swiper-slide');
    });
        ['next', 'prev'].forEach(bc => {
        let div = document.createElement('div');
        let divClass = `swiper-button-${bc}`;
        div.classList.add(divClass);
        wrapper.appendChild(div);
        cfg.navigation[`${bc}El`] = '.' + divClass;
    });
    const carousel = new Swiper('.swiper', cfg);
}


function makeModals(){
    let selector = entities.map(ent => `a[href*="${ent}"]`).join(', ');
    let modals = new Modals(selector);
    modals.el = document.querySelector('main');
    modals.filterParentsSelector = '.gallery, html[id$="_index"], header, p';
    modals.linkFilter = (link) => {
        if (/^manuscript_show_\d+/.test(docId)){
            return true;
        }
        let table = link.closest('table');
        if (table){
            let thead = table.querySelector('thead');
            return (thead === null);
        }
    }
    modals.init();
}

function isIndex(entity) {
    let rex = new RegExp(`\\/${entity}\\/?$`, 'gi');
    return !(rex.test(window.location.href));
}












