import './admin.css';
import React from 'react';
import { createRoot } from 'react-dom/client';
import AppearanceBuilder from './appearance/Builder.jsx';

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('ufaq-appearance-builder-root');
  if (!el) return;

  const props = window.ufaqAppearanceData || {};
  const root = createRoot(el);
  root.render(<AppearanceBuilder {...props} />);
});
