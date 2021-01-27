/*
* Enhancements for the Manuscript Verse Miscellany Project
* @author Joey Takeda
* @author DHIL
* @started 2020
*
* */
import Modals from '../yarn/dhilux/js/dist/modals.bundle.js'

let entities = ['person','theme','region','period'];
let query = entities.map(e => `a[href*="${e}"]`).join(', ');
console.log(query);
let modals = new Modals(query);
modals.init();
console.log(modals);







