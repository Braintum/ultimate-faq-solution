import React, { useEffect, useMemo, useState } from "react";
import { toBase64, DEFAULT_SCHEMA, buildInitialState, resetToDefaults } from './helpers';
import { SettingsPanel, PreviewPanel, NotificationToast, DesignLibraryModal } from './components';
import { __ } from '@wordpress/i18n';

// ----------------------------- Main Builder -----------------------------
export default function AppearanceBuilder({
    previewBaseUrl = "/?preview=faq",
    initialValues = {},
    schema = DEFAULT_SCHEMA,
    iframeTitle = "FAQ appearance preview",
    postMessageTargetOrigin = "*",
    linkedGroups = [],
}) {
    const initialState = useMemo(() => {
        return buildInitialState(schema, initialValues);
    }, [schema, initialValues]);

    const [values, setValues] = useState(initialState);
    const [debouncedValues, setDebouncedValues] = useState(initialState);
    const [iframeLoaded, setIframeLoaded] = useState(false);
    const [isSaving, setIsSaving] = useState(false);
    const [notification, setNotification] = useState(null);
    // null = never saved in this session; number = group count at last save
    const [lastSaveGroupCount, setLastSaveGroupCount] = useState(null);
    const [showDesignLibrary, setShowDesignLibrary] = useState(false);
    const iframeRef = React.useRef(null);

    // Debounce values changes
    useEffect(() => {
        const timeoutId = setTimeout(() => {
            setDebouncedValues(values);
        }, 1000);
        return () => clearTimeout(timeoutId);
    }, [values]);

    // Build iframe src from debounced values
    const iframeSrc = useMemo(() => {
        const encoded = toBase64(debouncedValues);
        const sep = previewBaseUrl.includes("?") ? "&" : "?";
        return `${previewBaseUrl}${sep}appearance=${encoded}`;
    }, [previewBaseUrl, debouncedValues]);

    // Reset iframe loaded flag when URL changes
    useEffect(() => {
        setIframeLoaded(false);
    }, [iframeSrc]);

    // Intercept WordPress publish/update button to save settings first
    useEffect(() => {
        const handleFormSubmit = async (e) => {
            const publishButton = e.target.querySelector('#publish');
            if (!publishButton || !publishButton.contains(e.submitter)) {
                return;
            }
            e.preventDefault();
            e.stopPropagation();
            setIsSaving(true);
            try {
                const data = window.ufaqAppearanceData || {};
                const postId = data.postId || new URLSearchParams(window.location.search).get('post');
                const response = await fetch(data.saveEndpoint || '/wp-json/ufaqsw/v1/appearance/save', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': data.nonce || '' },
                    body: JSON.stringify({ post_id: postId, settings: values }),
                });
                if (!response.ok) throw new Error();
                const form = e.target;
                form.removeEventListener('submit', handleFormSubmit);
                form.submit();
            } catch {
                setNotification({ type: 'error', message: __('Failed to save settings. Please try again.', 'ufaqsw') });
                setTimeout(() => setNotification(null), 5000);
            } finally {
                setIsSaving(false);
            }
        };

        const form = document.querySelector('#post');
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
            return () => form.removeEventListener('submit', handleFormSubmit);
        }
    }, [values]);

    // Post message for immediate iframe updates after load
    useEffect(() => {
        if (!iframeLoaded) return;
        const win = iframeRef.current?.contentWindow;
        if (!win) return;
        try {
            win.postMessage({ type: "appearance:update", payload: values }, postMessageTargetOrigin);
        } catch { /* ignore */ }
    }, [values, iframeLoaded, postMessageTargetOrigin]);

    function setField(key, val) {
        setValues((prev) => ({ ...prev, [key]: val }));
    }

    function handleApplyPreset(name, settings) {
        setValues((prev) => ({ ...prev, ...settings }));
        const titleInput = document.getElementById('title');
        if (titleInput && name) {
            titleInput.value = name;
            titleInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    function handleReset() {
        setValues(resetToDefaults(schema));
    }

    async function handleSave() {
        setIsSaving(true);
        setNotification(null);
        try {
            const data = window.ufaqAppearanceData || {};
            const postId = data.postId || new URLSearchParams(window.location.search).get('post');
            const response = await fetch(data.saveEndpoint || '/wp-json/ufaqsw/v1/appearance/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': data.nonce || '' },
                body: JSON.stringify({ post_id: postId, settings: values }),
            });
            if (!response.ok) throw new Error();
            await response.json();

            // Record linked group count for the bottom status bar
            setLastSaveGroupCount(linkedGroups.length);

            const successMsg = linkedGroups.length > 0
                ? __('Settings saved! Changes are now live on all linked FAQ Groups.', 'ufaqsw')
                : __('Settings saved! Apply this appearance to a FAQ Group to see it live.', 'ufaqsw');

            setNotification({ type: 'success', message: successMsg });
            setTimeout(() => setNotification(null), 4000);
        } catch {
            setNotification({ type: 'error', message: __('Failed to save settings. Please try again.', 'ufaqsw') });
            setTimeout(() => setNotification(null), 5000);
        } finally {
            setIsSaving(false);
        }
    }

    return (
        <div className="flex gap-4 py-4">
            <SettingsPanel
                schema={schema}
                values={values}
                setField={setField}
                onReset={handleReset}
                onSave={handleSave}
                isSaving={isSaving}
                linkedGroups={linkedGroups}
                lastSaveGroupCount={lastSaveGroupCount}
                onOpenDesignLibrary={() => setShowDesignLibrary(true)}
            />

            <PreviewPanel
                iframeLoaded={iframeLoaded}
                iframeSrc={iframeSrc}
                iframeTitle={iframeTitle}
                iframeRef={iframeRef}
                setIframeLoaded={setIframeLoaded}
            />

            {notification && (
                <NotificationToast notification={notification} setNotification={setNotification} />
            )}

            <DesignLibraryModal
                isOpen={showDesignLibrary}
                onClose={() => setShowDesignLibrary(false)}
                onApply={handleApplyPreset}
            />
        </div>
    );
}
