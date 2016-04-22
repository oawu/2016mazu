//
//  MapsViewController.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <CoreLocation/CoreLocation.h>
#import "UIImageView+WebCache.h"
#import "Path.h"
#import "Header.h"
#import "Marker.h"
#import "AFHTTPRequestOperationManager.h"
@import GoogleMaps;

@interface MapsViewController : UIViewController <CLLocationManagerDelegate>

@property GMSMapView *mapView;
@property NSMutableArray<Marker *> *markers;

@property BOOL isLoading;
@property NSTimer *timer;

@property GMSPolyline *polyline;

@property CLLocationManager *locationManager;

+ (CLLocationCoordinate2D) oriLocation;
@end
