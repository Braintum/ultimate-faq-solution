import React from 'react';

/**
 * Preloader component displays a loading animation with customizable dot color and loading text.
 *
 * @component
 * @param {Object} props - Component props.
 * @param {string} [props.dotColor='#3498db'] - The color of the animated dots.
 * @param {string} props.preloader_text - The text to display below the loading animation.
 * @returns {JSX.Element} The rendered preloader component.
 */
const Preloader = ({ dotColor = '#3498db', preloader_text }) => {
  return (
    <div className="preloader">
      <div className="dots" style={{ '--dot-color': dotColor }}>
        <span></span><span></span><span></span>
      </div>
	  <span className="loading-text">{preloader_text}</span>
    </div>
  );
};

export default Preloader;
