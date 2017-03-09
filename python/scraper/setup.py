"""
A setuptools based setup module.
"""

from setuptools import setup, find_packages

setup(
    name='group_scraper',
    version='1.0.0',
    description='Scraper utility for FitBit',
    packages=find_packages(),
    install_requires=['elasticsearch>=2,<3'],
    entry_points={
        'console_scripts': [
            'rgscrape=group_scraper:main',
        ],
    }
)