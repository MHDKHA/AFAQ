import React, { useState } from 'react';
import { LineChart, BarChart, Bar, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-react';

const AssessmentReportDashboard = () => {
    const { assessment, domains, statistics, locale } = usePage().props;

    const [emailForm, setEmailForm] = useState({
        email: '',
        subject: '',
        message: '',
        sending: false,
        result: null
    });

    // Prepare data for pie chart
    const availabilityData = [
        { name: locale === 'ar' ? 'نعم' : 'Yes', value: statistics.availableItems, color: '#10B981' },
        { name: locale === 'ar' ? 'لا' : 'No', value: statistics.unavailableItems, color: '#EF4444' }
    ];

    // Prepare domain distribution data
    const getDomainDistributionData = () => {
        if (!domains || !assessment.items) return [];

        return domains.map(domain => {
            const domainCriteriaIds = domain.categories.flatMap(category =>
                category.criteria.map(criterion => criterion.id)
            );

            const availableCount = assessment.items.filter(
                item => domainCriteriaIds.includes(item.criteria_id) && item.is_available
            ).length;

            const unavailableCount = assessment.items.filter(
                item => domainCriteriaIds.includes(item.criteria_id) && !item.is_available
            ).length;

            return {
                name: locale === 'ar' ? domain.name_ar : domain.name,
                available: availableCount,
                unavailable: unavailableCount
            };
        });
    };

    // Download PDF Report
    const downloadReport = () => {
        window.open(`/assessment-reports/export-pdf/${assessment.id}?locale=${locale}`, '_blank');
    };

    // Handle email form changes
    const handleEmailFormChange = (e) => {
        const { name, value } = e.target;
        setEmailForm(prev => ({ ...prev, [name]: value }));
    };

    // Send report via email
    const sendReportEmail = (e) => {
        e.preventDefault();

        if (!emailForm.email) {
            setEmailForm(prev => ({
                ...prev,
                result: {
                    success: false,
                    message: locale === 'ar' ? 'يرجى إدخال عنوان البريد الإلكتروني' : 'Please enter an email address'
                }
            }));
            return;
        }

        setEmailForm(prev => ({ ...prev, sending: true, result: null }));

        Inertia.post(`/assessment-dashboard/${assessment.id}/send-report`, {
            email: emailForm.email,
            subject: emailForm.subject,
            message: emailForm.message,
            locale: locale
        }, {
            onSuccess: (page) => {
                setEmailForm(prev => ({
                    ...prev,
                    sending: false,
                    result: {
                        success: true,
                        message: locale === 'ar' ? 'تم إرسال التقرير بنجاح' : 'Report sent successfully'
                    },
                    email: '',
                    subject: '',
                    message: ''
                }));
            },
            onError: (errors) => {
                setEmailForm(prev => ({
                    ...prev,
                    sending: false,
                    result: {
                        success: false,
                        message: errors.message || (locale === 'ar' ? 'حدث خطأ أثناء إرسال التقرير' : 'Error sending the report')
                    }
                }));
            }
        });
    };

    return (
        <div className="p-4 bg-white dark:bg-gray-900 rounded-lg shadow-sm">
            {/* Rest of the component remains the same as in your original code */}
            {/* ... */}
        </div>
    );
};

export default AssessmentReportDashboard;
