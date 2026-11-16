/**
* Enhancements for the Manuscript Verse Miscellany Project
* @author Joey Takeda
* @author DHIL
* @started 2020
*
**/

// suppress console logging
console.log = function () {};

/**
 * Imports
 */
import A11YTables from "dhilux/js/a11y_tables.js";
import Accordion from 'dhilux/js/accordion.js';
// core version + navigation, pagination modules:
import Swiper, { Navigation } from 'swiper';
import TomSelect from 'tom-select';

/**
 * The current document id
 * @type {string}
 */
const docId = document.querySelector('html').id;


/**
 * Initialize
 */
(function(){
    makeHomepageGallery();
    makeA11YTables();
    makeAccordions();
    makeBetterSelects();

    // add base path to citation-link
    const citationLinks = [...document.getElementsByClassName('citation-link')]
    citationLinks.forEach((citationLink) => {
        if (window.location.protocol === 'file:') {
            citationLink.innerHTML = citationLink.href = `${window.location.protocol}//${window.location.pathname}`
        } else {
            citationLink.innerHTML = citationLink.href = `${window.location.origin}${window.location.pathname}`
        }
    })
    // update date on citation accessed
    const citationAccessedDates = [...document.getElementsByClassName('citation-accessed')]
    citationAccessedDates.forEach((citationAccessedDate) => {
        const date = new Date()
        citationAccessedDate.innerHTML = date.toLocaleDateString('en-GB', {day: 'numeric', month: 'short', year: 'numeric'})
    })
})();

function makeAccordions(){
   let accordions =  [...document.querySelectorAll('details.accordion')].map(d => {
       return new Accordion(d);
   });
}

function makeBetterSelects(){
    const opts = {
        maxOptions: null,
        copyClassesToDropdown: true

    }
    const plugins = {
        'clear_button':{
            'title':'Remove all selected options',
            'html' : function(thing){
                return `<button class="btn clear-button">
                        <svg viewBox="0 0 24 24" height="1rem" width="1rem">
                            <line x1="0" x2="24" y1="0" y2="24" />
                            <line x1="24" x2="0" y1="0" y2="24" />
                        </svg>
                        <span class="visually-hidden">Close</span>
                    </button>`;
            }
        },
        remove_button: {
            title: 'Remove this item',
        },
        'checkbox_options': true
    };

    let advancedForm = document.querySelector('.search_filters');
    if (!advancedForm){
        return;
    }
    advancedForm.querySelectorAll('select').forEach(sel => {
        // If this is the sorter select, then we leave it as is
        if (sel.id == 'sort'){
            return sel;
        }
        let multiple = sel.hasAttribute('multiple');
        sel.classList.remove('form-control');
        plugins['checkbox_options'] = multiple;
        new TomSelect(sel, {
            ...opts,
            multiple,
            plugins
        });
    });

}

/**
 * Create the homepage gallery slider, using Swiper
 * @return {boolean} True if successful, false otherwise
 */
function makeHomepageGallery() {
    try{
        let q = '.archive-gallery';
        let gallery = document.querySelector(q);
        // Bail if no archive gallery
        if (!gallery) {
            return true;
        }
        // Add the navigation module to Swiper
        Swiper.use([Navigation]);
        let cfg = {
            // centeredSlides: true,
            spaceBetween: 16,
            slidesPerView: 3,
            // loop: true,
            navigation: {}
        }

        // Now create the swiper specific classes
        let wrapper = document.createElement('div');
        gallery.insertAdjacentElement('beforebegin', wrapper);
        wrapper.appendChild(gallery);
        wrapper.classList.add('swiper');
        gallery.classList.add('swiper-wrapper');
        [...gallery.children].forEach(child => {
            child.classList.add('swiper-slide');
        });
        // Create next/prev buttons
        ['next', 'prev'].forEach(bc => {
            let div = document.createElement('div');
            let divClass = `swiper-button-${bc}`;
            div.classList.add(divClass);
            wrapper.appendChild(div);
            cfg.navigation[`${bc}El`] = '.' + divClass;
        });
        // And create the carousel
        const carousel = new Swiper('.swiper', cfg);
        return true;
    } catch(e) {
        return false;
    }
}

/**
 * Function to create accessible tables using DHILUX's A11YTables
 * @return {boolean} True if successful, false otherwise
 */
function makeA11YTables(){
    try{
        let accessibleTables = new A11YTables();
        accessibleTables.init();
        // Wrap each table in a div for horizontal scrolling
        accessibleTables.tables.forEach(A11YTable => {
            let table = A11YTable.table;
            let ctr = document.createElement('div')
            table.insertAdjacentElement('beforebegin', ctr);
            ctr.classList.add('table-container');
            ctr.appendChild(table);
        });
        return true;
    } catch(e){
        console.log(e);
        return false;
    }
}

/**
 * Check whether we're currently on an index page for creating modals;
 * (i.e. don't create a modal for the link to the archive when we're on
 * archive_index; presumably, if you're on the index for that entity,
 * you want to go directly to it)
 * @param entity {string} The entity name
 * @returns {boolean} Whether we're on the index page
 */
function isIndex(entity) {
    let rex = new RegExp(`\\/${entity}\\/?$`, 'gi');
    return !(rex.test(window.location.href));
}












