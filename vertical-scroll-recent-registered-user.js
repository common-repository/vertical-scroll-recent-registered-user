/**
 *     Vertical scroll recent registered user
 *     Copyright (C) 2011 - 2022 www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	

function scrollVSRRU() {
	objVSRRU.scrollTop = objVSRRU.scrollTop + 1;
	vsrru_scrollPos++;
	if ((vsrru_scrollPos%vsrru_heightOfElm) == 0) {
		vsrru_numScrolls--;
		if (vsrru_numScrolls == 0) {
			objVSRRU.scrollTop = '0';
			vsrruContent();
		} else {
			if (vsrru_scrollOn == 'true') {
				vsrruContent();
			}
		}
	} else {
		setTimeout("scrollVSRRU();", 10);
	}
}

var vsrruNum = 0;
/*
Creates amount to show + 1 for the scrolling ability to work
scrollTop is set to top position after each creation
Otherwise the scrolling cannot happen
*/
function vsrruContent() {
	var tmp_vsrru = '';

	w_vsrru = vsrruNum - parseInt(vsrru_numberOfElm);
	if (w_vsrru < 0) {
		w_vsrru = 0;
	} else {
		w_vsrru = w_vsrru%vsrru.length;
	}
	
	// Show amount of vsrru
	var elementsTmp_vsrru = parseInt(vsrru_numberOfElm) + 1;
	for (i_vsrru = 0; i_vsrru < elementsTmp_vsrru; i_vsrru++) {
		
		tmp_vsrru += vsrru[w_vsrru%vsrru.length];
		w_vsrru++;
	}

	objVSRRU.innerHTML 	= tmp_vsrru;
	
	vsrruNum 			= w_vsrru;
	vsrru_numScrolls 	= vsrru.length;
	objVSRRU.scrollTop 	= '0';
	// start scrolling
	setTimeout("scrollVSRRU();", 2000);
}