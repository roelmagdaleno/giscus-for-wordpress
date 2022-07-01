const responses = {
	bad: {
		color: '#cf222e',
		message: 'Cannot use giscus on this repository. Make sure all of the above criteria has been met.',
	},
	default: {
		color: '',
		message: 'A public GitHub repository. This repo is where the discussions will be linked to.',
	},
	good: {
		color: '#1a7f37',
		message: 'Success! This repository meets all of the above criteria.',
	},
};

const debounce = (callback, wait) => {
	let timeoutId = null;
	return (...args) => {
		window.clearTimeout(timeoutId);
		timeoutId = window.setTimeout(() => {
			callback.apply(null, args);
		}, wait);
	};
}

const searchCategories = debounce( async ( ev ) => {
	const repository   = ev.target.value.trim();
	const spinnerEl    = ev.target.nextElementSibling;
	const descriptionEl = ev.target.nextElementSibling.nextElementSibling;

	descriptionEl.removeAttribute( 'style' );
	descriptionEl.innerHTML = responses.default.message;

	if ( '' === repository ) {
		return;
	}

	spinnerEl.classList.add( 'is-active' );

	const endpoint = `https://giscus.app/api/discussions/categories?repo=${ repository }`;
	const response = await fetch( endpoint );
	const type     = ! response.ok ? 'bad' : 'good';

	descriptionEl.style.color = responses[ type ].color;
	descriptionEl.innerHTML   = responses[ type ].message;

	spinnerEl.classList.remove( 'is-active' );

	if ( ! response.ok ) {
		return;
	}

	const data = await response.json();
	fillCategories( data );
}, 450 );

function fillCategories( data ) {
	const categoryEl = document.querySelector( '#category' );

	if ( ! categoryEl || ! data || data.categories.length === 0 ) {
		return;
	}

	const options = data.categories.map( ( category ) => {
		return `<option value="${ category.id }">${ category.name }</option>`;
	} );

	categoryEl.innerHTML = options.join( '' );

	const categories   = document.querySelector( '#categories' );
	const categoryId   = document.querySelector( '#categoryId' );
	const repositoryId = document.querySelector( '#repositoryId' );

	categories.value   = JSON.stringify( data.categories );
	categoryId.value   = data.categories[0].id;
	repositoryId.value = data.repositoryId;
}

function gsListeners() {
	const repositoryInput = document.querySelector( '#repository' );

	if ( ! repositoryInput ) {
		return;
	}

	repositoryInput.addEventListener( 'input', searchCategories );
}

document.addEventListener( 'DOMContentLoaded', gsListeners );
