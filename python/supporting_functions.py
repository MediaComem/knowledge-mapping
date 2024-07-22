# -*- coding: utf-8 -*-
"""
Support for AYA
"""
__author__ = """Giovanni Colavizza"""

import codecs, csv, string, time
import numpy as np
from sklearn.metrics.pairwise import euclidean_distances
from collections import defaultdict

def compare_authors(a,b):

	name_a = a[0]
	name_b = b[0]
	surname_a = a[1]
	surname_b = b[1]
	# remove excessive whiespace
	name_a = name_a.strip().lower()
	name_b = name_b.strip().lower()

	# this covers most missmatches
	#if name_a != name_b:
	#	return False

	surname_a = surname_a.strip().lower()
	surname_b = surname_b.strip().lower()

	# this covers most missmatches
	if surname_a != surname_b:
		return False

	# first take out full stops
	name_a = name_a.replace("."," ")
	name_b = name_b.replace("."," ")

	# check all components of names, in order of length, and gives a match only if all components of the shorter name match.
	name_a = sorted(name_a.split(),key=lambda x:len(x),reverse=True)
	name_b = sorted(name_b.split(),key=lambda x:len(x),reverse=True)
	if len(name_a) >= len(name_b):
		for x in name_b:
			if x not in name_a:
				return False
	else:
		for x in name_a:
			if x not in name_b:
				return False

	return True