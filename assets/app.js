// import './macromanhq-html/assets/js/main'

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';



// start the Stimulus application
import 'bootstrap';
window.bootstrap = require("bootstrap");

import {
	library,
	dom,
	config
} from "@fortawesome/fontawesome-svg-core";

import {
	faGem
} from "@fortawesome/free-solid-svg-icons/faGem";

import {
	faChevronRight
} from "@fortawesome/free-solid-svg-icons/faChevronRight";

import {
	faChevronDown
} from "@fortawesome/free-solid-svg-icons/faChevronDown";

import {
	faBars
} from "@fortawesome/free-solid-svg-icons/faBars";

import {
	faTimes
} from "@fortawesome/free-solid-svg-icons/faTimes";

import {
	faCheckCircle
} from "@fortawesome/free-solid-svg-icons/faCheckCircle";

import {
	faList
} from "@fortawesome/free-solid-svg-icons/faList";

import {
	faFilter
} from "@fortawesome/free-solid-svg-icons/faFilter";

import {
	faShapes
} from "@fortawesome/free-solid-svg-icons/faShapes";


import {
	faFacebook
} from "@fortawesome/free-brands-svg-icons";

config.searchPseudoElements = true;
config.mutateApproach = 'sync';

library.add(faGem);
library.add(faChevronRight);
library.add(faChevronDown);
library.add(faFacebook);
library.add(faBars);
library.add(faTimes);
library.add(faCheckCircle);
library.add(faList);
library.add(faFilter);
library.add(faShapes);

dom.watch();