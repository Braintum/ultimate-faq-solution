import React from 'react';

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
