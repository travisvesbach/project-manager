/*
takes two arguments:

v-outside-click="{
exclude: ['button'], // refs to be excluded/ignored
handler: 'onClose'   // method
}"


import like this:
import OutsideClick from '@/Directives/OutsideClick'

directives: {
    OutsideClick
},


*/


let OutsideClick = {
    bind (el, binding, vnode) {
        // Here's the click/touchstart handler
        // (it is registered below)
        OutsideClick = (e) => {
            e.stopPropagation()
            // Get the handler method name and the exclude array
            // from the object used in v-closable
            const { handler, exclude } = binding.value

            // This variable indicates if the clicked element is excluded
            let clickedOnExcludedEl = false
            exclude.forEach(refName => {
                // We only run this code if we haven't detected
                // any excluded element yet
                if (!clickedOnExcludedEl) {
                    // Get the element using the reference name
                    const excludedEl = vnode.context.$refs[refName];
                    let excludedDomEl = null;
                    if (excludedEl) {
                        // If it's a vue component grab the element, otherwise it is the element
                        excludedDomEl = excludedEl.$el ? excludedEl.$el : excludedEl;
                        clickedOnExcludedEl = excludedDomEl.contains(e.target);
                    }
                }
            });

            // temp exception for flatpickr calendar
            clickedOnExcludedEl = clickedOnExcludedEl ? clickedOnExcludedEl : document.getElementsByClassName('flatpickr-calendar')[0].contains(e.target);

            // We check to see if the clicked element is not
            // the dialog element and not excluded
            if (!el.contains(e.target) && !clickedOnExcludedEl) {
                // If the clicked element is outside the dialog
                // and not the button, then call the outside-click handler
                // from the same component this directive is used in
                vnode.context[handler]()
            }
        }
        // Register click/touchstart event listeners on the whole page
        document.addEventListener('click', OutsideClick)
        document.addEventListener('touchstart', OutsideClick)
    },

    unbind () {
        // If the element that has v-closable is removed, then
        // unbind click/touchstart listeners from the whole page
        document.removeEventListener('click', OutsideClick)
        document.removeEventListener('touchstart', OutsideClick)
    }
}

export default OutsideClick;
