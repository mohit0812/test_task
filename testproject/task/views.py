from django.shortcuts import render
from task.models import *
from task.forms import *
from django.http import JsonResponse
from django.core import serializers



def showlisting(request):
    if request.method == 'POST':

        form = Samplefile(data=request.POST, files=request.FILES)
        
        if form.is_valid():
            form.save()
            pdf_data=serializers.serialize("json", Sample_Files.objects.all())
            return JsonResponse(pdf_data,safe=False)

        else:
            print('invalid form')
            

    data=Sample.objects.prefetch_related('owner').filter(User_parent__isnull=True)
    pdf_data=Sample_Files.objects.all()
    return render(request, "frontend/listing.html",{'data':data , 'userform':Samplefile ,'pdf':pdf_data}) 