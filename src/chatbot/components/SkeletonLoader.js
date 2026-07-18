import React from 'react';

const SkeletonLoader = () => (
	<div className="skeleton-loader">
		{[1, 2, 3, 4].map((i) => (
			<div key={i} className="skeleton-card">
				<div className="skeleton-line title"></div>
				<div className="skeleton-line subtitle"></div>
			</div>
		))}
	</div>
);

export default SkeletonLoader;
