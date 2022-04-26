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
	faFacebook
} from "@fortawesome/free-brands-svg-icons";

config.searchPseudoElements = true;
config.mutateApproach = 'sync';

library.add(faGem);
library.add(faChevronRight);
library.add(faFacebook);

dom.watch();